<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('api.auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('balance')->group(function () {
        Route::get('/check', [BalanceController::class, 'checkStatus']);
        Route::get('/', [BalanceController::class, 'show']);
        Route::post('/set-initial', [BalanceController::class, 'setInitial']);

        //Untuk tujuan pengembangan 
        Route::post('/reset', [BalanceController::class, 'reset']);
    });

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
});
