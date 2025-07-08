<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\TransactionType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID kategori dari database (dari CategorySeeder)
        $gaji = Category::where('name', 'Gaji')->first()->id;
        $tunjangan = Category::where('name', 'Tunjangan')->first()->id;
        $thr = Category::where('name', 'THR')->first()->id;
        $lembur = Category::where('name', 'Lembur')->first()->id;
        $transport = Category::where('name', 'Transport')->first()->id;
        $sewaKost = Category::where('name', 'Sewa Kost')->first()->id;
        $uangMakan = Category::where('name', 'Uang Makan')->first()->id;
        $lainnya = Category::where('name', 'Lainnya')->first()->id;

        $transactions = [
            [
                'tgl_transaksi' => Carbon::create(2025, 1, 1),
                'deskripsi' => 'Gaji bulanan Januari',
                'tipe' => TransactionType::Pemasukan->value,
                'nominal' => 5000000.00,
                'kategori_id' => $gaji,
            ],
            [
                'tgl_transaksi' => Carbon::create(2025, 1, 5),
                'deskripsi' => 'Pembayaran sewa kost',
                'tipe' => TransactionType::Pengeluaran->value,
                'nominal' => 1500000.00,
                'kategori_id' => $sewaKost,
            ],
            [
                'tgl_transaksi' => Carbon::create(2025, 2, 1),
                'deskripsi' => 'Tunjangan kinerja',
                'tipe' => TransactionType::Pemasukan->value,
                'nominal' => 1000000.00,
                'kategori_id' => $tunjangan,
            ],
            [
                'tgl_transaksi' => Carbon::create(2025, 2, 10),
                'deskripsi' => 'Biaya transportasi',
                'tipe' => TransactionType::Pengeluaran->value,
                'nominal' => 500000.00,
                'kategori_id' => $transport,
            ],
            [
                'tgl_transaksi' => Carbon::create(2025, 3, 15),
                'deskripsi' => 'THR Lebaran',
                'tipe' => TransactionType::Pemasukan->value,
                'nominal' => 3000000.00,
                'kategori_id' => $thr,
            ],
            [
                'tgl_transaksi' => Carbon::create(2025, 3, 20),
                'deskripsi' => 'Uang makan mingguan',
                'tipe' => TransactionType::Pengeluaran->value,
                'nominal' => 200000.00,
                'kategori_id' => $uangMakan,
            ],
            [
                'tgl_transaksi' => Carbon::create(2025, 4, 1),
                'deskripsi' => 'Lembur proyek',
                'tipe' => TransactionType::Pemasukan->value,
                'nominal' => 750000.00,
                'kategori_id' => $lembur,
            ],
            [
                'tgl_transaksi' => Carbon::create(2025, 4, 5),
                'deskripsi' => 'Belanja kebutuhan lain',
                'tipe' => TransactionType::Pengeluaran->value,
                'nominal' => 300000.00,
                'kategori_id' => $lainnya,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
