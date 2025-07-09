<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Transaksi - Money Tracker')]
class TransactionList extends Component
{
    use WithPagination;

    // Filter properties
    public $search = '';
    public $filterType = '';
    public $filterCategory = '';
    public $filterMonth = '';
    public $filterYear = '';

    public function mount()
    {
        $this->filterYear = date('Y');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterMonth()
    {
        $this->resetPage();
    }

    public function updatedFilterYear()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterType', 'filterCategory', 'filterMonth']);
        $this->filterYear = date('Y');
        $this->resetPage();

        session()->flash('info', 'Filter berhasil dibersihkan.');
    }

    public function deleteTransaction($id)
    {
        try {
            $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
            $transaction->delete();

            session()->flash('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus transaksi.');
        }
    }

    protected $listeners = [
        'transactionSaved' => '$refresh'
    ];

    public function addTransaction()
    {
        $this->dispatch('openCreateModal');
    }

    public function editTransaction($id)
    {
        $this->dispatch('openEditModal', $id);
    }

    public function render()
    {
        // Build query
        $query = Transaction::where('user_id', auth()->id())
            ->with('category')
            ->latest('tgl_transaksi');

        // Apply filters
        if ($this->search) {
            $query->where('deskripsi', 'like', '%' . $this->search . '%');
        }

        if ($this->filterType) {
            $query->where('tipe', $this->filterType);
        }

        if ($this->filterCategory) {
            $query->where('kategori_id', $this->filterCategory);
        }

        if ($this->filterMonth) {
            $query->filterByMonth($this->filterMonth, $this->filterYear ?: date('Y'));
        } elseif ($this->filterYear) {
            $query->filterByYear($this->filterYear);
        }

        // Get transactions with pagination
        $transactions = $query->paginate(10);

        // Get categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('livewire.transactions.transaction-list', [
            'transactions' => $transactions,
            'categories' => $categories,
            'title' => 'Daftar Transaksi'
        ]);
    }
}
