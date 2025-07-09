<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kategori - Money Tracker')]
class CategoryManager extends Component
{
    use WithPagination;

    // Properties for category form
    public $name = '';
    public $editingId = null;
    public $isEditing = false;

    // Properties for search and filter
    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Properties for modals
    public $showModal = false;
    public $showDeleteModal = false;
    public $deletingId = null;

    // Properties for category details
    public $selectedCategory = null;
    public $categoryStats = [];

    protected $rules = [
        'name' => 'required|string|max:100|unique:categories,name',
    ];

    protected $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.max' => 'Nama kategori maksimal 100 karakter.',
        'name.unique' => 'Nama kategori sudah ada.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function openEditModal($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->showModal = true;
        $this->isEditing = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        // Dynamic validation for edit (exclude current category from unique check)
        $rules = $this->rules;
        if ($this->isEditing) {
            $rules['name'] = 'required|string|max:100|unique:categories,name,' . $this->editingId;
        }

        $this->validate($rules);

        try {
            if ($this->isEditing) {
                $category = Category::findOrFail($this->editingId);
                $category->update(['name' => $this->name]);

                session()->flash('success', 'Kategori berhasil diperbarui.');
            } else {
                Category::create(['name' => $this->name]);

                session()->flash('success', 'Kategori berhasil ditambahkan.');
            }

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deletingId = null;
        $this->showDeleteModal = false;
    }

    public function delete()
    {
        try {
            $category = Category::findOrFail($this->deletingId);

            // Check if category has transactions
            $transactionCount = Transaction::where('kategori_id', $this->deletingId)->count();

            if ($transactionCount > 0) {
                session()->flash('error', "Kategori tidak dapat dihapus karena masih memiliki {$transactionCount} transaksi terkait.");
                $this->cancelDelete();
                return;
            }

            $category->delete();
            session()->flash('success', 'Kategori berhasil dihapus.');

            $this->cancelDelete();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function viewDetails($id)
    {
        $category = Category::with(['transactions' => function ($query) {
            $query->where('user_id', auth()->id())
                ->orderBy('tgl_transaksi', 'desc');
        }])->findOrFail($id);

        $this->selectedCategory = $category;

        // Calculate stats
        $transactions = $category->transactions;
        $totalTransactions = $transactions->count();
        $totalIncome = $transactions->where('tipe', 'pemasukan')->sum('nominal');
        $totalExpense = $transactions->where('tipe', 'pengeluaran')->sum('nominal');
        $recentTransactions = $transactions->take(5);

        $this->categoryStats = [
            'total_transactions' => $totalTransactions,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_amount' => $totalIncome - $totalExpense,
            'recent_transactions' => $recentTransactions
        ];
    }

    public function closeDetails()
    {
        $this->selectedCategory = null;
        $this->categoryStats = [];
    }

    private function resetForm()
    {
        $this->name = '';
        $this->editingId = null;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        // Build query for categories
        $query = Category::withCount(['transactions' => function ($query) {
            $query->where('user_id', auth()->id());
        }]);

        // Apply search filter
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        // Get paginated categories
        $categories = $query->paginate(10);

        // Calculate summary stats
        $totalCategories = Category::count();
        $categoriesWithTransactions = Category::whereHas('transactions', function ($query) {
            $query->where('user_id', auth()->id());
        })->count();
        $categoriesWithoutTransactions = $totalCategories - $categoriesWithTransactions;

        return view('livewire.categories.category-manager', [
            'categories' => $categories,
            'totalCategories' => $totalCategories,
            'categoriesWithTransactions' => $categoriesWithTransactions,
            'categoriesWithoutTransactions' => $categoriesWithoutTransactions,
            'title' => 'Manajemen Kategori'
        ]);
    }
}
