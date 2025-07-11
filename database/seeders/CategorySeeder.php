<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // System category (harus pertama)
            'Saldo Awal',

            // Income categories  
            'Gaji',
            'Tunjangan',
            'Bonus',
            'THR',
            'Lembur',
            'Freelance',
            'Investasi',
            'Hadiah',

            // Expense categories
            'Makanan & Minuman',
            'Transportasi',
            'Belanja',
            'Tagihan',
            'Hiburan',
            'Kesehatan',
            'Pendidikan',
            'Sewa/Kost',
            'Lain-lain'
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category],
                ['name' => $category]
            );
        }

        $this->command->info("Created " . count($categories) . " categories including 'Saldo Awal'");
    }
}
