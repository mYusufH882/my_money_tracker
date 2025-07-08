<?php

namespace App\Models;

use App\TransactionType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'tgl_transaksi',
        'deskripsi',
        'tipe',
        'nominal',
        'kategori_id',
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
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, '.', ',');
    }

    // Accessor untuk format date
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d/m/Y');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
