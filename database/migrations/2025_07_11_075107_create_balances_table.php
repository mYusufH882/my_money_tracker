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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('initial_balance', 15, 2)->comment('Saldo awal yang diinput user');
            $table->decimal('current_balance', 15, 2)->comment('Saldo saat ini setelah transaksi');
            $table->timestamp('last_updated')->useCurrent()->comment('Kapan saldo terakhir diupdate');
            $table->timestamps();

            $table->unique('user_id');

            $table->index(['user_id', 'current_balance']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
