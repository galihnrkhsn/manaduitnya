<?php

use App\Http\Controllers\BudgetC;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardC;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardC::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
        Route::get('', [TransactionController::class, 'index'])->name('index');
        Route::get('create', [TransactionController::class, 'create'])->name('create');
        Route::get('money', [TransactionController::class, 'money'])->name('money');
        Route::post('store', [TransactionController::class, 'store'])->name('store');
        Route::get('{transaction}', [TransactionController::class, 'show'])->whereUuid('transaction')->name('show');
        Route::get('{transaction}/edit', [TransactionController::class, 'edit'])->whereUuid('transaction')->name('edit');
        Route::put('{transaction}', [TransactionController::class, 'update'])->whereUuid('transaction')->name('update');
        Route::delete('{transaction}', [TransactionController::class, 'destroy'])->whereUuid('transaction')->name('destroy');
    });

    Route::group(['prefix' => 'budgets', 'as' => 'budgets.'], function () {
        Route::get('', [BudgetC::class, 'index'])->name('index');
        Route::get('create', [BudgetC::class, 'create'])->name('create');
        Route::get('edit/{budget}', [BudgetC::class, 'edit'])->name('edit');
        Route::get('show', [BudgetC::class, 'show'])->name('show');
        Route::post('store', [BudgetC::class, 'store'])->name('store');
        Route::put('update', [BudgetC::class, 'update'])->name('update');
        Route::delete('destroy/{budget}', [BudgetC::class, 'destroy'])->name('destroy');
    });

    Route::view('/more', 'more')->name('more');

    Route::group(['prefix' => 'wallets', 'as' => 'wallets.'], function () {
        Route::get('', [WalletController::class, 'index'])->name('index');
        Route::get('create', [WalletController::class, 'create'])->name('create');
        Route::get('edit/{wallet}', [WalletController::class, 'edit'])->name('edit');
        Route::post('store', [WalletController::class, 'store'])->name('store');
        Route::put('update', [WalletController::class, 'update'])->name('update');
        Route::delete('destroy', [WalletController::class, 'destroy'])->name('destroy');
        Route::patch('restore', [WalletController::class, 'restore'])->name('restore');
    });

    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('', [CategoryController::class, 'index'])->name('index');
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::get('edit/{category}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::put('update', [CategoryController::class, 'update'])->name('update');
        Route::delete('destroy', [CategoryController::class, 'destroy'])->name('destroy');
        Route::patch('restore', [CategoryController::class, 'restore'])->name('restore');
    });

    // Route::get('more', fn () => view('more'))->name('more');

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('update', [ProfileController::class, 'update'])->name('update');
        Route::delete('destroy', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
