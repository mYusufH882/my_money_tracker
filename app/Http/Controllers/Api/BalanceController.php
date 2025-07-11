<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetInitialBalanceRequest;
use App\Http\Resources\BalanceResource;
use App\Models\Balance;
use App\Models\Category;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    use ApiResponseTrait;

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $balance = $user->balance;

        if (!$balance) return $this->errorResponse('Balance not set. Please set initial balance first.', 404);

        return $this->successResponse(new BalanceResource($balance));
    }

    public function checkStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        $hasBalance = $user->hasBalance();

        return $this->successResponse([
            'has_balance' => $hasBalance,
            'current_balance' => $hasBalance ? $user->getCurrentBalance() : null,
            'formatted_current_balance' => $hasBalance ? $user->formatted_current_balance : null,
            'message' => $hasBalance ? 'Balance is set' : 'Please set your initial balance first'
        ]);
    }

    public function setInitial(SetInitialBalanceRequest $request): JsonResponse
    {
        $user = $request->user();

        // Check if balance already exists
        if ($user->hasBalance()) {
            return $this->errorResponse('Initial balance already set. Cannot set again.', 400);
        }

        $initialBalance = $request->initial_balance;

        try {
            DB::beginTransaction();

            // Create balance record
            $balance = Balance::create([
                'user_id' => $user->id,
                'initial_balance' => $initialBalance,
                'current_balance' => $initialBalance, // Current = initial at first
                'last_updated' => now()
            ]);

            // Get or create "Saldo Awal" category
            $category = Category::firstOrCreate(
                ['name' => 'Saldo Awal'],
                ['name' => 'Saldo Awal']
            );

            // Create transaction record for audit trail
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'tgl_transaksi' => now()->toDateString(),
                'deskripsi' => 'Saldo Awal - Dana awal masuk ke aplikasi',
                'tipe' => 'pemasukan',
                'nominal' => $initialBalance,
                'kategori_id' => $category->id
            ]);

            DB::commit();

            return $this->createdResponse([
                'balance' => new BalanceResource($balance),
                'initial_transaction' => [
                    'id' => $transaction->id,
                    'deskripsi' => $transaction->deskripsi,
                    'nominal' => $transaction->nominal,
                    'formatted_nominal' => 'Rp ' . number_format($transaction->nominal, 0, ',', '.'),
                    'tgl_transaksi' => $transaction->tgl_transaksi
                ]
            ], 'Initial balance set successfully with transaction record created');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to set initial balance: ' . $e->getMessage(), 500);
        }
    }

    public function reset(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->balance()?->delete();
        $user->transactions()?->delete();

        return $this->successResponse(null, 'Balance reset successfully. You can now set a new initial balance.');
    }
}
