<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\Category;
use App\Services\BalanceService;
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

    public $currentBalance = 0;
    public $previewBalance = 0;
    public $showBalancePreview = false;
    public $balanceWarning = '';

    protected $balanceService;

    protected $listeners = [
        'openCreateModal' => 'openCreate',
        'openEditModal' => 'openEdit',
    ];

    public function boot(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

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

    public function mount()
    {
        $this->loadCurrentBalance();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->tgl_transaksi = date('Y-m-d');
        $this->loadCurrentBalance();
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
        $this->loadCurrentBalance();
        $this->showModal = true;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['tipe', 'nominal'])) {
            $this->updateBalancePreview();
        }

        $this->validateOnly($propertyName);
    }

    public function updateBalancePreview()
    {
        if (!$this->tipe || !$this->nominal || !is_numeric($this->nominal)) {
            $this->showBalancePreview = false;
            $this->balanceWarning = '';
            return;
        }

        $simulation = $this->balanceService->simulateTransaction(
            auth()->user(),
            (float) $this->nominal,
            $this->tipe
        );

        $this->previewBalance = $simulation['new_balance'];
        $this->showBalancePreview = true;

        if ($this->previewBalance < 0) {
            $this->balanceWarning = 'Saldo akan menjadi negatif!';
        } elseif ($this->tipe === 'pengeluaran' && $this->previewBalance < 100000) {
            $this->balanceWarning = 'Saldo akan menjadi rendah (< Rp 100.000)';
        } else {
            $this->balanceWarning = '';
        }
    }

    public function save()
    {
        $this->validate();

        $canProceed = $this->balanceService->canMakeTransaction(
            auth()->user(),
            (float) $this->nominal,
            $this->tipe
        );

        if (!$canProceed['can_proceed']) {
            session()->flash('error', $canProceed['reason']);
            return;
        }

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

                $this->balanceService->updateFromTransaction(
                    auth()->user(),
                    $transaction->nominal,
                    $transaction->tipe === 'pemasukan' ? 'pengeluaran' : 'pemasukan'
                );

                $transaction->update($data);

                $this->balanceService->updateFromTransaction(
                    auth()->user(),
                    (float) $this->nominal,
                    $this->tipe
                );

                $message = 'Transaksi berhasil diperbarui.';
            } else {
                Transaction::create($data);

                $this->balanceService->updateFromTransaction(
                    auth()->user(),
                    (float) $this->nominal,
                    $this->tipe
                );

                $message = 'Transaksi berhasil ditambahkan.';
            }

            $this->closeModal();
            $this->dispatch('transactionSaved');
            $this->dispatch('balance-updated');

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
        $this->reset(['tgl_transaksi', 'deskripsi', 'tipe', 'nominal', 'kategori_id', 'transactionId', 'previewBalance', 'showBalancePreview', 'balanceWarning']);
        $this->resetErrorBag();
    }

    private function loadCurrentBalance()
    {
        $user = auth()->user();
        $this->currentBalance = $user->getCurrentBalance();
    }

    public function getFormattedCurrentBalanceProperty()
    {
        return 'Rp ' . number_format($this->currentBalance, 0, ',', '.');
    }

    public function getFormattedPreviewBalanceProperty()
    {
        return 'Rp ' . number_format($this->previewBalance, 0, ',', '.');
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        return view('livewire.transactions.transaction-form', [
            'categories' => $categories
        ]);
    }
}
