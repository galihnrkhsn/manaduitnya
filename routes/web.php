<?php

use App\Http\Controllers\BudgetC;
use App\Http\Controllers\DashboardC;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardC::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
        Route::get('', [TransactionController::class, 'index'])->name('index');
        Route::get('create', [TransactionController::class, 'create'])->name('create');
        Route::get('edit', [TransactionController::class, 'edit'])->name('edit');
        Route::get('show', [TransactionController::class, 'show'])->name('show');
        Route::post('store', [TransactionController::class, 'store'])->name('store');
        Route::get('money', [TransactionController::class, 'money'])->name('money');
    });

    Route::group(['prefix' => 'budgets', 'as' => 'budgets.'], function () {
        Route::get('', [BudgetC::class, 'index'])->name('index');
        Route::get('create', [BudgetC::class, 'create'])->name('create');
        Route::get('edit/{budget}', [BudgetC::class, 'edit'])->name('edit');
        Route::get('show', [BudgetC::class, 'show'])->name('show');
        Route::post('store', [BudgetC::class, 'store'])->name('store');
        Route::put('update', [BudgetC::class, 'update'])->name('update');
        Route::delete('destroy', [BudgetC::class, 'destroy'])->name('destroy');
    });

    Route::get('more', [TransactionController::class, 'index'])->name('more');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
