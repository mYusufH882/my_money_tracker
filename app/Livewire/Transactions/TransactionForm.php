<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\Category;
use Livewire\Component;

class TransactionForm extends Component
{
    public $showModal = false;
    public $isEdit = false;
    public $transactionId = null;

    // Form fields
    public $tgl_transaksi = '';
    public $deskripsi = '';
    public $tipe = '';
    public $nominal = '';
    public $kategori_id = '';

    protected $listeners = [
        'openCreateModal' => 'openCreate',
        'openEditModal' => 'openEdit',
    ];

    public function rules()
    {
        return [
            'tgl_transaksi' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0.01',
            'kategori_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'tgl_transaksi.required' => 'Tanggal transaksi wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'tipe.required' => 'Tipe transaksi wajib dipilih',
            'nominal.required' => 'Nominal wajib diisi',
            'nominal.min' => 'Nominal minimal Rp 0,01',
            'kategori_id.required' => 'Kategori wajib dipilih',
        ];
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->tgl_transaksi = date('Y-m-d');
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->find($id);

        if (!$transaction) {
            session()->flash('error', 'Transaksi tidak ditemukan.');
            return;
        }

        $this->isEdit = true;
        $this->transactionId = $id;
        $this->tgl_transaksi = $transaction->tgl_transaksi->format('Y-m-d');
        $this->deskripsi = $transaction->deskripsi;
        $this->tipe = $transaction->tipe;
        $this->nominal = $transaction->nominal;
        $this->kategori_id = $transaction->kategori_id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'user_id' => auth()->id(),
                'tgl_transaksi' => $this->tgl_transaksi,
                'deskripsi' => $this->deskripsi,
                'tipe' => $this->tipe,
                'nominal' => $this->nominal,
                'kategori_id' => $this->kategori_id,
            ];

            if ($this->isEdit) {
                $transaction = Transaction::where('user_id', auth()->id())->findOrFail($this->transactionId);
                $transaction->update($data);
                $message = 'Transaksi berhasil diperbarui.';
            } else {
                Transaction::create($data);
                $message = 'Transaksi berhasil ditambahkan.';
            }

            $this->closeModal();
            $this->dispatch('transactionSaved');
            session()->flash('success', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['tgl_transaksi', 'deskripsi', 'tipe', 'nominal', 'kategori_id', 'transactionId']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        return view('livewire.transactions.transaction-form', [
            'categories' => $categories
        ]);
    }
}
