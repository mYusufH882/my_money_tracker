<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_transaksi');
            $table->text('deskripsi');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->decimal('nominal', 15, 2);
            $table->foreignId('kategori_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index('tgl_transaksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
