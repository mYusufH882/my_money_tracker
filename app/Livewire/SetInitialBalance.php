<?php

namespace App\Livewire;

use App\Models\Balance;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Set Saldo Awal')]
class SetInitialBalance extends Component
{
    public $initial_balance = '';
    public $isLoading = false;

    protected $rules = [
        'initial_balance' => 'required|numeric|min:0|max:999999999999.99',
    ];

    protected $messages = [
        'initial_balance.required' => 'Saldo awal wajib diisi',
        'initial_balance.numeric' => 'Saldo awal harus berupa angka',
        'initial_balance.min' => 'Saldo awal tidak boleh negatif',
        'initial_balance.max' => 'Saldo awal terlalu besar',
    ];

    public function mount()
    {
        if (auth()->user()->hasBalance()) {
            return redirect()->route('dashboard');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function setInitialBalance()
    {
        $this->validate();

        $this->isLoading = true;

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $initialBalance = (float) $this->initial_balance;

            // Create balance record
            Balance::create([
                'user_id' => $user->id,
                'initial_balance' => $initialBalance,
                'current_balance' => $initialBalance,
                'last_updated' => now()
            ]);

            // Get or create "Saldo Awal" category
            $category = Category::firstOrCreate(
                ['name' => 'Saldo Awal'],
                ['name' => 'Saldo Awal']
            );

            // Create transaction record for audit trail
            Transaction::create([
                'user_id' => $user->id,
                'tgl_transaksi' => now()->toDateString(),
                'deskripsi' => 'Saldo Awal - Dana awal masuk ke aplikasi',
                'tipe' => 'pemasukan',
                'nominal' => $initialBalance,
                'kategori_id' => $category->id
            ]);

            DB::commit();

            // Dispatch event untuk refresh komponen lain
            $this->dispatch('balance-set', [
                'initial_balance' => $initialBalance,
                'formatted_balance' => 'Rp ' . number_format($initialBalance, 0, ',', '.')
            ]);

            session()->flash('success', 'Saldo awal berhasil diset! Sekarang Anda bisa mulai mencatat transaksi.');

            // Redirect ke dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('error', 'Gagal mengatur saldo awal: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function formatInput()
    {
        // Format input saat user selesai mengetik
        if ($this->initial_balance && is_numeric($this->initial_balance)) {
            $this->initial_balance = number_format((float) $this->initial_balance, 0, '', '');
        }
    }

    public function getFormattedPreviewProperty()
    {
        if (!$this->initial_balance || !is_numeric($this->initial_balance)) {
            return 'Rp 0';
        }

        return 'Rp ' . number_format((float) $this->initial_balance, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.set-initial-balance');
    }
}
