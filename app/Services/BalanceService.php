<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    public function updateFromTransaction(User $user, float $amount, string $transactionType): bool
    {
        try {
            DB::beginTransaction();

            $balance = $user->balance;

            if (!$balance) {
                throw new Exception('Balance not set. Please set initial balance first.');
            }

            switch ($transactionType) {
                case 'pemasukan':
                    $balance->updateBalance($amount, 'add');
                    break;

                case 'pengeluaran':
                    if (!$balance->hasSufficientBalance($amount)) {
                        throw new Exception('Insufficient balance. Current balance: ' . $balance->formatted_current_balance);
                    }
                    $balance->updateBalance($amount, 'subtract');
                    break;

                default:
                    throw new Exception('Invalid transaction type: ' . $transactionType);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getBalanceSummary(User $user): array
    {
        $balance = $user->balance;

        if (!$balance) {
            return [
                'has_balance' => false,
                'initial_balance' => 0,
                'current_balance' => 0,
                'formatted_current_balance' => 'Belum diset',
                'last_updated' => null,
                'transactions_count' => $user->transactions()->count(),
                'total_income' => $user->transactions()->where('tipe', 'pemasukan')->sum('nominal'),
                'total_expense' => $user->transactions()->where('tipe', 'pengeluaran')->sum('nominal'),
            ];
        }

        return [
            'has_balance' => true,
            'initial_balance' => $balance->initial_balance,
            'current_balance' => $balance->current_balance,
            'formatted_current_balance' => $balance->formatted_current_balance,
            'last_updated' => $balance->last_updated,
            'transactions_count' => $user->transactions()->count(),
            'total_income' => $user->transactions()->where('tipe', 'pemasukan')->sum('nominal'),
            'total_expense' => $user->transactions()->where('tipe', 'pengeluaran')->sum('nominal'),
        ];
    }

    public function canMakeTransaction(User $user, float $amount, string $transactionType): array
    {
        $balance = $user->balance;

        if (!$balance) {
            return [
                'can_proceed' => false,
                'reason' => 'Please set your initial balance first.',
                'error_code' => 'NO_BALANCE',
            ];
        }

        if ($transactionType === 'pengeluaran' && !$balance->hasSufficientBalance($amount)) {
            return [
                'can_proceed' => false,
                'reason' => 'Insufficient balance. Current balance: ' . $balance->formatted_current_balance,
                'error_code' => 'INSUFFICIENT_BALANCE',
                'current_balance' => $balance->current_balance,
                'required_balance' => $amount,
            ];
        }

        return [
            'can_proceed' => true,
            'current_balance' => $balance->current_balance,
            'balance_after_transaction' => $transactionType === 'pemasukan'
                ? $balance->current_balance + $amount
                : $balance->current_balance - $amount
        ];
    }

    public function simulateTransaction(User $user, float $amount, string $transactionType): array
    {
        $balance = $user->balance;

        if (!$balance) {
            return [
                'error' => 'No balance set',
                'current_balance' => 0,
                'new_balance' => 0
            ];
        }

        $newBalance = $transactionType === 'pemasukan'
            ? $balance->current_balance + $amount
            : $balance->current_balance - $amount;

        return [
            'current_balance' => $balance->current_balance,
            'transaction_amount' => $amount,
            'transaction_type' => $transactionType,
            'new_balance' => $newBalance,
            'formatted_current' => $balance->formatted_current_balance,
            'formatted_new' => 'Rp ' . number_format($newBalance, 0, ',', '.'),
            'is_valid' => $newBalance >= 0
        ];
    }
}
