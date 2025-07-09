<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;

#[Layout('layouts.app')]
#[Title('Laporan Keuangan - My Money Tracker')]
class FinancialSummary extends Component
{
    // Filter properties
    public $selectedMonth;
    public $selectedYear;
    public $selectedCategory = '';

    // Report data
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $balance = 0;
    public $monthlyData = [];
    public $categoryBreakdown = [];
    public $recentTransactions = [];

    public function mount()
    {
        $this->selectedMonth = date('m');
        $this->selectedYear = date('Y');
        $this->loadReportData();
    }

    public function updatedSelectedMonth()
    {
        $this->loadReportData();
        $this->dispatch('reportDataUpdated');
    }

    public function updatedSelectedYear()
    {
        $this->loadReportData();
        $this->dispatch('reportDataUpdated');
    }

    public function updatedSelectedCategory()
    {
        $this->loadReportData();
        $this->dispatch('reportDataUpdated');
    }

    public function loadReportData()
    {
        if (!auth()->check()) {
            return;
        }

        $this->loadSummaryData();
        $this->loadMonthlyData();
        $this->loadCategoryBreakdown();
        $this->loadRecentTransactions();
    }

    private function loadSummaryData()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->filterByMonth($this->selectedMonth, $this->selectedYear);

        if ($this->selectedCategory) {
            $query->where('kategori_id', $this->selectedCategory);
        }

        $transactions = $query->get();

        $this->totalIncome = $transactions->where('tipe', 'pemasukan')->sum('nominal');
        $this->totalExpense = $transactions->where('tipe', 'pengeluaran')->sum('nominal');
        $this->balance = $this->totalIncome - $this->totalExpense;
    }

    private function loadMonthlyData()
    {
        $monthlyData = [];

        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $query = Transaction::where('user_id', auth()->id())
                ->filterByMonth($month, $year);

            if ($this->selectedCategory) {
                $query->where('kategori_id', $this->selectedCategory);
            }

            $income = $query->where('tipe', 'pemasukan')->sum('nominal');
            $expense = $query->where('tipe', 'pengeluaran')->sum('nominal');

            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
                'net' => (float) ($income - $expense)
            ];
        }

        $this->monthlyData = $monthlyData;
    }

    private function loadCategoryBreakdown()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->filterByMonth($this->selectedMonth, $this->selectedYear)
            ->whereNotNull('kategori_id')
            ->join('categories', 'transactions.kategori_id', '=', 'categories.id')
            ->selectRaw('categories.name, categories.id, tipe, SUM(nominal) as total, COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name', 'tipe');

        if ($this->selectedCategory) {
            $query->where('kategori_id', $this->selectedCategory);
        }

        $breakdown = $query->get();

        $this->categoryBreakdown = $breakdown
            ->groupBy('name')
            ->map(function ($group) {
                $income = $group->where('tipe', 'pemasukan')->sum('total') ?: 0;
                $expense = $group->where('tipe', 'pengeluaran')->sum('total') ?: 0;
                return [
                    'name' => $group->first()->name,
                    'income' => (float) $income,
                    'expense' => (float) $expense,
                    'net' => (float) ($income - $expense),
                    'transactions' => $group->sum('count'),
                ];
            })
            ->sortByDesc('transactions')
            ->values()
            ->toArray();
    }

    private function loadRecentTransactions()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->with('category')
            ->filterByMonth($this->selectedMonth, $this->selectedYear)
            ->orderBy('tgl_transaksi', 'desc')
            ->limit(10);

        if ($this->selectedCategory) {
            $query->where('kategori_id', $this->selectedCategory);
        }

        $this->recentTransactions = $query->get();
    }

    public function resetFilters()
    {
        $this->selectedMonth = date('m');
        $this->selectedYear = date('Y');
        $this->selectedCategory = '';
        $this->loadReportData();
        $this->dispatch('reportDataUpdated');

        session()->flash('info', 'Filter berhasil direset.');
    }

    public function exportExcel()
    {
        $this->dispatch('showLoading', 'Mengexport data ke Excel...');

        // Small delay to show loading
        usleep(500000); // 0.5 seconds

        return redirect()->route('export.excel');
    }

    public function exportPdf()
    {
        $this->dispatch('showLoading', 'Mengexport data ke PDF...');

        // Small delay to show loading
        usleep(500000); // 0.5 seconds

        return redirect()->route('export.pdf');
    }

    public function render()
    {
        // Get categories for filter
        $categories = Category::orderBy('name')->get();

        // Generate years for filter (current year and 2 years back)
        $years = collect(range(date('Y') - 2, date('Y')))->reverse()->values();

        // Generate months
        $months = collect([
            ['value' => '01', 'name' => 'Januari'],
            ['value' => '02', 'name' => 'Februari'],
            ['value' => '03', 'name' => 'Maret'],
            ['value' => '04', 'name' => 'April'],
            ['value' => '05', 'name' => 'Mei'],
            ['value' => '06', 'name' => 'Juni'],
            ['value' => '07', 'name' => 'Juli'],
            ['value' => '08', 'name' => 'Agustus'],
            ['value' => '09', 'name' => 'September'],
            ['value' => '10', 'name' => 'Oktober'],
            ['value' => '11', 'name' => 'November'],
            ['value' => '12', 'name' => 'Desember'],
        ]);

        return view('livewire.reports.financial-summary', [
            'categories' => $categories,
            'years' => $years,
            'months' => $months,
            'title' => 'Laporan Keuangan'
        ]);
    }
}
