<?php

namespace App\Models;

use App\Enums\TransactionType;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'tgl_transaksi',
        'deskripsi',
        'tipe',
        'nominal',
        'kategori_id',
        'user_id',
    ];

    protected $casts = [
        'tgl_transaksi' => 'date',
        'nominal' => 'decimal:2',
    ];

    // Scope untuk filter berdasarkan bulan
    public function scopeFilterByMonth($query, $month, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return $query->whereMonth('tgl_transaksi', $month)
            ->whereYear('tgl_transaksi', $year);
    }

    // Scope untuk filter berdasarkan tahun
    public function scopeFilterByYear($query, $year)
    {
        return $query->whereYear('tgl_transaksi', $year);
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeFilterByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    // Scope untuk pemasukan
    public function scopeIncome($query)
    {
        return $query->where('tipe', TransactionType::Pemasukan->value);
    }

    // Scope untuk pengeluaran
    public function scopeExpense($query)
    {
        return $query->where('tipe', TransactionType::Pengeluaran->value);
    }

    // Accessor untuk format currency
    public function getFormattedNominalAttribute()
    {
        return CurrencyHelper::format($this->nominal);
    }

    // Accessor untuk format date
    public function getFormattedTglTransaksiAttribute()
    {
        return $this->tgl_transaksi->format('d/m/Y');
    }

    // Accessor untuk format short currency
    public function getFormattedNominalShortAttribute()
    {
        return CurrencyHelper::formatForExport($this->nominal);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id', 'id');
    }
}
