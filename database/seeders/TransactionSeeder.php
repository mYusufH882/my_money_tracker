<?php

namespace Database\Seeders;

use App\Enums\TransactionType;
use App\Models\Balance;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Create test user
            $user = User::firstOrCreate(
                [
                    'email' => 'yusuf@mail.com'
                ],
                [
                    'name' => 'Yusuf',
                    'password' => Hash::make('password')
                ]
            );

            // Create initial balance for user
            $initialBalance = 3000000.00; // 3 juta saldo awal

            $balance = Balance::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'initial_balance' => $initialBalance,
                    'current_balance' => $initialBalance,
                    'last_updated' => Carbon::create(2024, 12, 31)
                ]
            );

            // Get categories (pastikan CategorySeeder sudah jalan)
            $saldoAwal = Category::where('name', 'Saldo Awal')->first()->id;
            $gaji = Category::where('name', 'Gaji')->first()->id;
            $tunjangan = Category::where('name', 'Tunjangan')->first()->id;
            $thr = Category::where('name', 'THR')->first()->id;
            $lembur = Category::where('name', 'Lembur')->first()->id;
            $transportasi = Category::where('name', 'Transportasi')->first()->id;
            $sewaKost = Category::where('name', 'Sewa/Kost')->first()->id;
            $makanan = Category::where('name', 'Makanan & Minuman')->first()->id;
            $lainnya = Category::where('name', 'Lain-lain')->first()->id;

            $transactions = [
                // Saldo awal (harus paling awal)
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2024, 12, 31),
                    'deskripsi' => 'Saldo Awal - Dana awal masuk ke aplikasi',
                    'tipe' => TransactionType::Pemasukan->value,
                    'nominal' => $initialBalance,
                    'kategori_id' => $saldoAwal,
                ],

                // Transaksi Januari 2025
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 1, 1),
                    'deskripsi' => 'Gaji bulanan Januari',
                    'tipe' => TransactionType::Pemasukan->value,
                    'nominal' => 5000000.00,
                    'kategori_id' => $gaji,
                ],
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 1, 5),
                    'deskripsi' => 'Pembayaran sewa kost',
                    'tipe' => TransactionType::Pengeluaran->value,
                    'nominal' => 1500000.00,
                    'kategori_id' => $sewaKost,
                ],
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 1, 10),
                    'deskripsi' => 'Makan siang kantin',
                    'tipe' => TransactionType::Pengeluaran->value,
                    'nominal' => 25000.00,
                    'kategori_id' => $makanan,
                ],

                // Transaksi Februari 2025
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 2, 1),
                    'deskripsi' => 'Tunjangan kinerja',
                    'tipe' => TransactionType::Pemasukan->value,
                    'nominal' => 1000000.00,
                    'kategori_id' => $tunjangan,
                ],
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 2, 10),
                    'deskripsi' => 'Biaya transportasi',
                    'tipe' => TransactionType::Pengeluaran->value,
                    'nominal' => 500000.00,
                    'kategori_id' => $transportasi,
                ],

                // Transaksi Maret 2025
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 3, 15),
                    'deskripsi' => 'THR Lebaran',
                    'tipe' => TransactionType::Pemasukan->value,
                    'nominal' => 3000000.00,
                    'kategori_id' => $thr,
                ],
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 3, 20),
                    'deskripsi' => 'Belanja keperluan lebaran',
                    'tipe' => TransactionType::Pengeluaran->value,
                    'nominal' => 750000.00,
                    'kategori_id' => $lainnya,
                ],

                // Transaksi April 2025
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 4, 1),
                    'deskripsi' => 'Lembur proyek',
                    'tipe' => TransactionType::Pemasukan->value,
                    'nominal' => 750000.00,
                    'kategori_id' => $lembur,
                ],
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 4, 5),
                    'deskripsi' => 'Belanja kebutuhan sehari-hari',
                    'tipe' => TransactionType::Pengeluaran->value,
                    'nominal' => 300000.00,
                    'kategori_id' => $lainnya,
                ],

                // Transaksi Juli 2025 (current month)
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 7, 1),
                    'deskripsi' => 'Gaji bulanan Juli',
                    'tipe' => TransactionType::Pemasukan->value,
                    'nominal' => 5200000.00,
                    'kategori_id' => $gaji,
                ],
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 7, 5),
                    'deskripsi' => 'Bayar sewa kost Juli',
                    'tipe' => TransactionType::Pengeluaran->value,
                    'nominal' => 1500000.00,
                    'kategori_id' => $sewaKost,
                ],
                [
                    'user_id' => $user->id,
                    'tgl_transaksi' => Carbon::create(2025, 7, 10),
                    'deskripsi' => 'Makan di restaurant',
                    'tipe' => TransactionType::Pengeluaran->value,
                    'nominal' => 150000.00,
                    'kategori_id' => $makanan,
                ],
            ];

            // Calculate final balance berdasarkan semua transaksi
            $finalBalance = $initialBalance;

            foreach ($transactions as $transaction) {
                // Skip saldo awal karena sudah di-set di initial balance
                if ($transaction['kategori_id'] === $saldoAwal) {
                    Transaction::create($transaction);
                    continue;
                }

                // Create transaction
                Transaction::create($transaction);

                // Update running balance
                if ($transaction['tipe'] === 'pemasukan') {
                    $finalBalance += $transaction['nominal'];
                } else {
                    $finalBalance -= $transaction['nominal'];
                }
            }

            // Update final balance
            $balance->update([
                'current_balance' => $finalBalance,
                'last_updated' => Carbon::create(2025, 7, 10, 14, 30)
            ]);

            $this->command->info("User created: {$user->email}");
            $this->command->info("Initial balance: Rp " . number_format($initialBalance, 0, ',', '.'));
            $this->command->info("Final balance: Rp " . number_format($finalBalance, 0, ',', '.'));
            $this->command->info("Total transactions: " . count($transactions));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->error("Transaction seeding failed: " . $e->getMessage());
            throw $e;
        }
    }
}
