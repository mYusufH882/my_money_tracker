<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Services\BalanceService;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    protected $balanceService;

    public function __construct(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $query = Transaction::where('user_id', request()->user()->id);

        if (request()->filled('month')) {
            $query->filterByMonth(request('month'), request('year', Carbon::now()->year));
        } elseif (request()->filled('year')) {
            $query->filterByYear(request('year'));
        }

        if (request()->filled('tipe')) {
            $query->filterByType(request('tipe'));
        }

        if (request()->filled('kategori_id')) {
            $query->where('kategori_id', request('kategori_id'));
        }

        $transactions = $query->with('category')->paginate(10);

        // Add balance summary to pagination response
        $balanceSummary = $this->balanceService->getBalanceSummary(request()->user());

        return $this->successWithPaginationAndSummary(
            TransactionResource::collection($transactions),
            $balanceSummary
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $user = $request->user();

        // Check if user can make this transaction
        $canProceed = $this->balanceService->canMakeTransaction(
            $user,
            $request->nominal,
            $request->tipe
        );

        if (!$canProceed['can_proceed']) {
            return $this->errorResponse($canProceed['reason'], 400, [
                'error_code' => $canProceed['error_code'],
                'additional_data' => $canProceed
            ]);
        }

        try {
            DB::beginTransaction();

            // Create transaction
            $transactionData = $request->validated();
            $transactionData['user_id'] = $user->id;

            $transaction = Transaction::create($transactionData);
            $transaction->load('category');

            // Update balance
            $this->balanceService->updateFromTransaction(
                $user,
                $request->nominal,
                $request->tipe
            );

            DB::commit();

            // Get updated balance summary
            $balanceSummary = $this->balanceService->getBalanceSummary($user);

            return $this->createdResponseWithSummary(
                new TransactionResource($transaction),
                $balanceSummary,
                'Transaction created successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create transaction: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $transaction = Transaction::with('category')
            ->where('user_id', request()->user()->id)
            ->find($id);

        if (!$transaction) {
            return $this->notFoundResponse('Transaction not found');
        }

        return $this->successResponse(new TransactionResource($transaction));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, $id): JsonResponse
    {
        $user = $request->user();
        $transaction = Transaction::where('user_id', $user->id)->find($id);

        if (!$transaction) {
            return $this->notFoundResponse('Transaction not found');
        }

        try {
            DB::beginTransaction();

            // Reverse old transaction effect on balance
            $this->balanceService->updateFromTransaction(
                $user,
                $transaction->nominal,
                $transaction->tipe === 'pemasukan' ? 'pengeluaran' : 'pemasukan'
            );

            // Check if user can make the new transaction
            $canProceed = $this->balanceService->canMakeTransaction(
                $user,
                $request->nominal,
                $request->tipe
            );

            if (!$canProceed['can_proceed']) {
                // Restore old transaction effect
                $this->balanceService->updateFromTransaction(
                    $user,
                    $transaction->nominal,
                    $transaction->tipe
                );

                DB::rollBack();
                return $this->errorResponse($canProceed['reason'], 400);
            }

            // Update transaction
            $transaction->update($request->validated());
            $transaction->load('category');

            // Apply new transaction effect on balance
            $this->balanceService->updateFromTransaction(
                $user,
                $request->nominal,
                $request->tipe
            );

            DB::commit();

            // Get updated balance summary
            $balanceSummary = $this->balanceService->getBalanceSummary($user);

            return $this->updatedResponseWithSummary(
                new TransactionResource($transaction),
                $balanceSummary,
                'Transaction updated successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update transaction: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $user = request()->user();
        $transaction = Transaction::where('user_id', $user->id)->find($id);

        if (!$transaction) {
            return $this->notFoundResponse('Transaction not found');
        }

        try {
            DB::beginTransaction();

            // Reverse transaction effect on balance
            $this->balanceService->updateFromTransaction(
                $user,
                $transaction->nominal,
                $transaction->tipe === 'pemasukan' ? 'pengeluaran' : 'pemasukan'
            );

            // Delete transaction
            $transaction->delete();

            DB::commit();

            // Get updated balance summary
            $balanceSummary = $this->balanceService->getBalanceSummary($user);

            return $this->deletedResponseWithSummary(
                $balanceSummary,
                'Transaction deleted successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete transaction: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Simulate transaction (preview without saving)
     */
    public function simulate(): JsonResponse
    {
        $user = request()->user();

        request()->validate([
            'nominal' => 'required|numeric|min:0.01',
            'tipe' => 'required|in:pemasukan,pengeluaran'
        ]);

        $simulation = $this->balanceService->simulateTransaction(
            $user,
            request('nominal'),
            request('tipe')
        );

        return $this->successResponse($simulation, 'Transaction simulation');
    }

    public function report(): JsonResponse
    {
        $year = request('year', Carbon::now()->year);
        $month = request('month', null);

        $query = Transaction::where('user_id', request()->user()->id);

        if ($month) {
            $query->filterByMonth($month, $year);
        } else {
            $query->filterByYear($year);
        }

        $report = [
            'total_income' => $query->clone()->income()->sum('nominal') ?? 0,
            'total_expense' => $query->clone()->expense()->sum('nominal') ?? 0,
            'balance' => ($query->clone()->income()->sum('nominal') ?? 0) - ($query->clone()->expense()->sum('nominal') ?? 0),
            'by_category' => $query->clone()
                ->selectRaw('categories.name as category_name, categories.id as kategori_id, tipe, SUM(nominal) as total')
                ->leftJoin('categories', 'transactions.kategori_id', '=', 'categories.id')
                ->groupBy('categories.name', 'categories.id', 'tipe')
                ->get(),
        ];

        return $this->successResponse($report, 'Report generated successfully');
    }

    public function chart(): JsonResponse
    {
        $year = request('year', Carbon::now()->year);
        $query = Transaction::where('user_id', request()->user()->id)->filterByYear($year);

        $chartData = [
            'monthly' => $query->clone()
                ->selectRaw('MONTH(tgl_transaksi) as month, tipe, SUM(nominal) as total')
                ->groupBy('month', 'tipe')
                ->get()
                ->groupBy('month')
                ->map(function ($group) {
                    return [
                        'month' => (int) $group->first()->month,
                        'income' => $group->where('tipe', 'pemasukan')->sum('total') ?? 0,
                        'expense' => $group->where('tipe', 'pengeluaran')->sum('total') ?? 0,
                    ];
                })->values()->isEmpty() ? collect(range(1, 12))->map(function ($month) {
                    return [
                        'month' => $month,
                        'income' => 0,
                        'expense' => 0,
                    ];
                }) : $query->clone()
                ->selectRaw('MONTH(tgl_transaksi) as month, tipe, SUM(nominal) as total')
                ->groupBy('month', 'tipe')
                ->get()
                ->groupBy('month')
                ->map(function ($group) {
                    return [
                        'month' => (int) $group->first()->month,
                        'income' => $group->where('tipe', 'pemasukan')->sum('total') ?? 0,
                        'expense' => $group->where('tipe', 'pengeluaran')->sum('total') ?? 0,
                    ];
                })->values(),
        ];

        return $this->successResponse($chartData, 'Chart data retrieved successfully');
    }

    public function export(): BinaryFileResponse
    {
        try {
            return Excel::download(new TransactionsExport(), 'transactions.xlsx');
        } catch (\Exception $e) {
            abort(500, 'Export failed: ' . $e->getMessage());
        }
    }

    public function exportPdf(): BinaryFileResponse
    {
        try {
            return Excel::download(new TransactionsExport(), 'transactions.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        } catch (\Exception $e) {
            abort(500, 'PDF Export failed: ' . $e->getMessage());
        }
    }
}
