<?php

namespace App\Livewire;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard - Money Tracker')]
class Dashboard extends Component
{
    public $currentMonth;
    public $currentYear;
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $balance = 0;
    public $recentTransactions = [];
    public $monthlyChart = [];
    public $dailyBreakdown = [];

    public function mount()
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->loadDashboardData();
    }

    public function updated($propertyName)
    {
        // Reload data when month/year changes
        if (in_array($propertyName, ['currentMonth', 'currentYear'])) {
            $this->loadDashboardData();
        }
    }

    public function loadDashboardData()
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return;
        }

        // Summary untuk bulan ini
        $this->totalIncome = Transaction::where('user_id', auth()->id())
            ->filterByMonth($this->currentMonth, $this->currentYear)
            ->where('tipe', 'pemasukan')
            ->sum('nominal') ?: 0;

        $this->totalExpense = Transaction::where('user_id', auth()->id())
            ->filterByMonth($this->currentMonth, $this->currentYear)
            ->where('tipe', 'pengeluaran')
            ->sum('nominal') ?: 0;

        $this->balance = $this->totalIncome - $this->totalExpense;

        // Transaksi terbaru bulan ini saja (10 terakhir)
        $this->recentTransactions = Transaction::where('user_id', auth()->id())
            ->filterByMonth($this->currentMonth, $this->currentYear)
            ->with('category')
            ->latest('tgl_transaksi')
            ->latest('id')
            ->take(10)
            ->get()
            ->toArray();

        // Data chart 6 bulan terakhir
        $this->loadMonthlyChart();

        // Breakdown transaksi hari ini
        $this->loadDailyBreakdown();
    }

    private function loadMonthlyChart()
    {
        if (!auth()->check()) {
            $this->monthlyChart = [];
            return;
        }

        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $income = Transaction::where('user_id', auth()->id())
                ->filterByMonth($month, $year)
                ->where('tipe', 'pemasukan')
                ->sum('nominal') ?: 0;

            $expense = Transaction::where('user_id', auth()->id())
                ->filterByMonth($month, $year)
                ->where('tipe', 'pengeluaran')
                ->sum('nominal') ?: 0;

            $months[] = [
                'label' => $date->format('M Y'),
                'month' => $date->format('M'),
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
        }

        $this->monthlyChart = $months;
    }

    private function loadDailyBreakdown()
    {
        if (!auth()->check()) {
            $this->dailyBreakdown = [];
            return;
        }

        // Get today's transactions by category
        $today = Carbon::today();

        $breakdown = Transaction::where('user_id', auth()->id())
            ->whereDate('tgl_transaksi', $today)
            ->with('category')
            ->selectRaw('kategori_id, tipe, SUM(nominal) as total, COUNT(*) as count')
            ->groupBy('kategori_id', 'tipe')
            ->get();

        $grouped = $breakdown->groupBy('kategori_id');

        $this->dailyBreakdown = $grouped->map(function ($group) {
            $categoryName = 'Tanpa Kategori';
            $firstTransaction = $group->first();

            // Get category name safely
            if ($firstTransaction && $firstTransaction->kategori_id) {
                $category = $group->first()->category;
                if ($category) {
                    $categoryName = $category->name;
                }
            }

            $income = $group->where('tipe', 'pemasukan')->sum('total') ?: 0;
            $expense = $group->where('tipe', 'pengeluaran')->sum('total') ?: 0;

            return [
                'category_name' => $categoryName,
                'income' => (float) $income,
                'expense' => (float) $expense,
                'net' => (float) ($income - $expense),
                'total_transactions' => $group->sum('count'),
            ];
        })->values()->toArray();
    }

    public function changeMonth($direction)
    {
        if ($direction === 'prev') {
            if ($this->currentMonth == 1) {
                $this->currentMonth = 12;
                $this->currentYear--;
            } else {
                $this->currentMonth--;
            }
        } elseif ($direction === 'next') {
            if ($this->currentMonth == 12) {
                $this->currentMonth = 1;
                $this->currentYear++;
            } else {
                $this->currentMonth++;
            }
        }

        // Force reload data
        $this->loadDashboardData();

        // Dispatch event untuk update chart
        $this->dispatch('monthChanged');

        // Force refresh component
        $this->skipRender = false;
    }

    public function exportExcel()
    {
        return redirect()->route('export.excel');
    }

    public function exportPdf()
    {
        return redirect()->route('export.pdf');
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'title' => 'Dashboard'
        ]);
    }
}
