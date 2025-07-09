<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard - My Money Tracker')]
class Dashboard extends Component
{
    public $currentMonth;
    public $currentYear;
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $balance = 0;
    public $recentTransactions = [];
    public $monthlyChart = [];
    public $categoryBreakdown = [];

    public function mount()
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Summary untuk bulan ini
        $this->totalIncome = Transaction::forCurrentUser()
            ->filterByMonth($this->currentMonth, $this->currentYear)
            ->where('tipe', 'pemasukan')
            ->sum('nominal');

        $this->totalExpense = Transaction::forCurrentUser()
            ->filterByMonth($this->currentMonth, $this->currentYear)
            ->where('tipe', 'pengeluaran')
            ->sum('nominal');

        $this->balance = $this->totalIncome - $this->totalExpense;

        // Transaksi terbaru (5 terakhir)
        $this->recentTransactions = Transaction::forCurrentUser()
            ->with('category')
            ->latest('tgl_transaksi')
            ->take(5)
            ->get()
            ->toArray();

        // Data chart 6 bulan terakhir
        $this->loadMonthlyChart();

        // Breakdown kategori bulan ini
        $this->loadCategoryBreakdown();
    }

    private function loadMonthlyChart()
    {
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $income = Transaction::forCurrentUser()
                ->filterByMonth($month, $year)
                ->where('tipe', 'pemasukan')
                ->sum('nominal');

            $expense = Transaction::forCurrentUser()
                ->filterByMonth($month, $year)
                ->where('tipe', 'pengeluaran')
                ->sum('nominal');

            $months[] = [
                'label' => $date->format('M Y'),
                'month' => $date->format('M'),
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
        }

        $this->monthlyChart = $months;
    }

    private function loadCategoryBreakdown()
    {
        $this->categoryBreakdown = Transaction::forCurrentUser()
            ->filterByMonth($this->currentMonth, $this->currentYear)
            ->join('categories', 'transactions.kategori_id', '=', 'categories.id')
            ->selectRaw('categories.name, categories.id, tipe, SUM(nominal) as total, COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name', 'tipe')
            ->orderBy('total', 'desc')
            ->get()
            ->groupBy('name')
            ->map(function ($group) {
                $income = $group->where('tipe', 'pemasukan')->sum('total');
                $expense = $group->where('tipe', 'pengeluaran')->sum('total');
                return [
                    'name' => $group->first()->name,
                    'income' => (float) $income,
                    'expense' => (float) $expense,
                    'net' => (float) ($income - $expense),
                    'transactions' => $group->sum('count'),
                ];
            })
            ->values()
            ->toArray();
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
        } else {
            if ($this->currentMonth == 12) {
                $this->currentMonth = 1;
                $this->currentYear++;
            } else {
                $this->currentMonth++;
            }
        }

        $this->loadDashboardData();
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
