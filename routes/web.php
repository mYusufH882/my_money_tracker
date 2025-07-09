<?php

use App\Exports\TransactionsExport;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Categories\CategoryManager;
use App\Livewire\Dashboard;
use App\Livewire\Reports\FinancialSummary;
use App\Livewire\Transactions\TransactionList;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/transactions', TransactionList::class)->name('transactions');
    Route::get('/reports', FinancialSummary::class)->name('reports');
    Route::get('/categories', CategoryManager::class)->name('categories');

    // Logout route
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    // Export routes (akan dihandle di Livewire components)
    Route::get('/export/excel', function () {
        return Excel::download(new TransactionsExport(), 'transactions.xlsx');
    })->name('export.excel');

    Route::get('/export/pdf', function () {
        return Excel::download(new TransactionsExport(true), 'transactions.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    })->name('export.pdf');
});
