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

class TransactionController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $query = Transaction::query();

        if (request()->has('month')) {
            $query->filterByMonth(request('month'), request('year', Carbon::now()->year));
        } elseif (request()->has('year')) {
            $query->filterByYear(request('year'));
        }

        if (request()->has('tipe')) {
            $query->filterByType(request('tipe'));
        }

        if (request()->has('kategori_id')) {
            $query->where('kategori_id', request('kategori_id'));
        }

        $transactions = $query->with('category')->paginate(10);

        return $this->successWithPagination(TransactionResource::collection($transactions));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $transaction = Transaction::create($request->validated());
        $transaction->load('category');

        return $this->createdResponse(new TransactionResource($transaction), 'Transaction created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $transaction = Transaction::with('category')->find($id);

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
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return $this->notFoundResponse('Transaction not found');
        }

        $transaction->update($request->validated());
        $transaction->load('category');

        return $this->updatedResponse(new TransactionResource($transaction), 'Transaction updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return $this->notFoundResponse('Transaction not found');
        }

        $transaction->delete();

        return $this->deletedResponse('Transaction deleted successfully');
    }

    public function report(): JsonResponse
    {
        $year = request('year', Carbon::now()->year);
        $month = request('month', null);

        $query = Transaction::query();

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
        $query = Transaction::query()->filterByYear($year);

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

    public function export(): \Sympony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new TransactionsExport(), 'transactions.xlsx');
    }

    public function exportPdf(): \Sympony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new TransactionsExport(), 'transactions.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }
}
