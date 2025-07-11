<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'initial_balance',
        'current_balance',
        'last_updated'
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'last_updated' => 'datetime'
    ];

    /**
     * Get the user that owns the balance
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update current balance and last_updated timestamp
     */
    public function updateBalance(float $amount, string $operation = 'add'): void
    {
        if ($operation === 'add') {
            $this->current_balance += $amount;
        } else {
            $this->current_balance -= $amount;
        }

        $this->last_updated = now();
        $this->save();
    }

    /**
     * Check if balance is sufficient for withdrawal
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->current_balance >= $amount;
    }

    /**
     * Get formatted current balance
     */
    public function getFormattedCurrentBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->current_balance, 0, ',', '.');
    }

    /**
     * Get formatted initial balance
     */
    public function getFormattedInitialBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->initial_balance, 0, ',', '.');
    }
}
