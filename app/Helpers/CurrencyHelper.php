<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format($amount, $withSymbol = true, $withDecimals = false)
    {
        $decimals = $withDecimals ? 2 : 0;
        $formatted = number_format($amount, $decimals, ',', '.');

        return $withSymbol ? 'Rp ' . $formatted : $formatted;
    }

    public static function formatForExport($amount)
    {
        if ($amount >= 1000000000) {
            // Milyar
            return 'Rp ' . number_format($amount / 1000000000, 1, ',', '.') . 'M';
        } elseif ($amount >= 1000000) {
            // Juta
            return 'Rp ' . number_format($amount / 1000000, 1, ',', '.') . 'Jt';
        } elseif ($amount >= 1000) {
            // Ribu
            return 'Rp ' . number_format($amount / 1000, 0, ',', '.') . 'rb';
        } else {
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }
    }

    public static function getColorForType($type, $amount = null)
    {
        switch ($type) {
            case 'pemasukan':
                return '059669'; // Green
            case 'pengeluaran':
                return 'DC2626'; // Red
            default:
                if ($amount !== null) {
                    return $amount >= 0 ? '059669' : 'DC2626';
                }
                return '374151'; // Gray
        }
    }
}
