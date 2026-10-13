<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', static function (): JsonResponse {
    return response()->json([
        'framework' => 'Laravel',
        'message' => 'Hello from Laravel and FrankenPHP on Vercel',
    ]);
})->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');
});

Route::get('/health', static function (): JsonResponse {
    return response()->json(['status' => 'ok']);
})->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');
});
// Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::group(['prefix' => 'transaksi', 'as' => 'transactions.'], function () {
        Route::get('', [TransactionController::class, 'index'])->name('index');
        Route::get('create', [TransactionController::class, 'create'])->name('create');
        Route::get('show', [TransactionController::class, 'show'])->name('show');
        Route::post('store', [TransactionController::class, 'store'])->name('store');
        Route::get('money', [TransactionController::class, 'money'])->name('money');
    });

    Route::group(['prefix' => 'bugdet', 'as' => 'budgets.'], function () {
        Route::get('', [TransactionController::class, 'index'])->name('index');
        Route::get('create', [TransactionController::class, 'create'])->name('create');
        Route::get('show', [TransactionController::class, 'show'])->name('show');
        Route::post('store', [TransactionController::class, 'store'])->name('store');
    });

    Route::get('more', [TransactionController::class, 'index'])->name('more');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
})->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');
});

require __DIR__.'/auth.php';
