<?php

namespace Database\Seeders;

use App\Models\Category;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $categories = [
                ['name' => 'Gaji'],
                ['name' => 'Tunjangan'],
                ['name' => 'THR'],
                ['name' => 'Lembur'],
                ['name' => 'Transport'],
                ['name' => 'Sewa Kost'],
                ['name' => 'Uang Makan'],
                ['name' => 'Lainnya']
            ];

            foreach ($categories as $category) {
                Category::firstOrCreate($category);
            }

            DB::commit();
        } catch (Exception $e) {
            throw new Exception("Categories not found. Please run CategorySeeder first. Error: " . $e->getMessage());
        }
    }
}
