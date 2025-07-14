<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\Category;
use App\Services\BalanceService;
use Illuminate\Support\Facades\DB;
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

    public $currentBalance = 0;
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $filteredIncome = 0;
    public $filteredExpense = 0;
    public $transactionCount = 0;

    protected $balanceService;

    protected $listeners = [
        'transactionSaved' => 'refreshData',
        'balance-updated' => 'refreshData'
    ];

    public function boot(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function mount()
    {
        $this->filterYear = date('Y');
        $this->loadBalanceSummary();
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
            DB::beginTransaction();

            $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);

            $this->balanceService->updateFromTransaction(
                auth()->user(),
                $transaction->nominal,
                $transaction->tipe === 'pemasukan' ? 'pengeluaran' : 'pemasukan'
            );

            $transaction->delete();

            DB::commit();

            $this->refreshData();
            session()->flash('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menghapus transaksi.');
        }
    }

    public function refreshData()
    {
        $this->loadBalanceSummary();
        $this->dispatch('$refresh');
    }

    public function addTransaction()
    {
        $this->dispatch('openCreateModal');
    }

    public function editTransaction($id)
    {
        $this->dispatch('openEditModal', $id);
    }

    public function loadBalanceSummary()
    {
        $user = auth()->user();

        $this->currentBalance = $user->getCurrentBalance();

        $allTransactions = Transaction::where('user_id', $user->id)->get();
        $this->totalIncome = $allTransactions->where('tipe', 'pemasukan')->sum('nominal');
        $this->totalExpense = $allTransactions->where('tipe', 'pengeluaran')->sum('nominal');
        $this->transactionCount = $allTransactions->count();

        $filteredQuery = $this->getFilteredQuery();
        $filteredTransactions = $filteredQuery->get();
        $this->filteredIncome = $filteredTransactions->where('tipe', 'pemasukan')->sum('nominal');
        $this->filteredExpense = $filteredTransactions->where('tipe', 'pengeluaran')->sum('nominal');
    }

    private function getFilteredQuery()
    {
        $query = Transaction::where('user_id', auth()->id());

        if ($this->search) {
            $query->where('deskripsi', 'like', '%' . $this->search . '%');
        }

        if ($this->filterType) {
            $query->where('tipe', $this->filterType);
        }

        if ($this->filterCategory) {
            $query->where('kategori_id', $this->filterCategory);
        }

        if ($this->filterMonth && $this->filterYear) {
            $query->filterByMonth($this->filterMonth, $this->filterYear);
        } elseif ($this->filterYear) {
            $query->filterByYear($this->filterYear);
        }

        return $query;
    }

    public function getFormattedCurrentBalanceProperty()
    {
        return 'Rp ' . number_format($this->currentBalance, 0, ',', '.');
    }

    public function getFormattedTotalIncomeProperty()
    {
        return 'Rp ' . number_format($this->totalIncome, 0, ',', '.');
    }

    public function getFormattedTotalExpenseProperty()
    {
        return 'Rp ' . number_format($this->totalExpense, 0, ',', '.');
    }

    public function getFormattedFilteredIncomeProperty()
    {
        return 'Rp ' . number_format($this->filteredIncome, 0, ',', '.');
    }

    public function getFormattedFilteredExpenseProperty()
    {
        return 'Rp ' . number_format($this->filteredExpense, 0, ',', '.');
    }

    public function getFilteredNetProperty()
    {
        return $this->filteredIncome - $this->filteredExpense;
    }

    public function getFormattedFilteredNetProperty()
    {
        return 'Rp ' . number_format($this->filteredNet, 0, ',', '.');
    }

    public function render()
    {
        // Build query
        $query = $this->getFilteredQuery();

        $transactions = $query->with('category')
            ->latest('tgl_transaksi')
            ->latest('id')
            ->paginate(10);

        // Get categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('livewire.transactions.transaction-list', [
            'transactions' => $transactions,
            'categories' => $categories,
        ]);
    }
}
