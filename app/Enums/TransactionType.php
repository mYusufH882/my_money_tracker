<?php

namespace App\Enums;

enum TransactionType: string
{
    case Pemasukan = 'pemasukan';
    case Pengeluaran = 'pengeluaran';
}
