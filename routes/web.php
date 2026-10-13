<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/transaksi', 'placeholder', ['title' => 'Transaksi'])->name('transactions.index');
    Route::view('/catat', 'placeholder', ['title' => 'Catat transaksi'])->name('transactions.create');
    Route::view('/budget', 'placeholder', ['title' => 'Budget'])->name('budgets.index');
    Route::view('/lainnya', 'more')->name('more');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
