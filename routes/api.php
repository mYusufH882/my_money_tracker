<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{category}', [CategoryController::class, 'show']);
    Route::put('/{category}', [CategoryController::class, 'update']);
    Route::delete('/{category}', [CategoryController::class, 'destroy']);
});

Route::prefix('transactions')->group(function () {
    Route::get('/report', [TransactionController::class, 'report']);
    Route::get('/chart', [TransactionController::class, 'chart']);
    Route::get('/export', [TransactionController::class, 'export']);
    Route::get('/export-pdf', [TransactionController::class, 'exportPdf']);

    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/', [TransactionController::class, 'store']);
    Route::get('/{transaction}', [TransactionController::class, 'show']);
    Route::put('/{transaction}', [TransactionController::class, 'update']);
    Route::delete('/{transaction}', [TransactionController::class, 'destroy']);
});
