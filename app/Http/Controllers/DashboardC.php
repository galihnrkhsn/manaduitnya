<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardC extends Controller
{
    public function index() {
        $user               = auth()->user();
        $wallets            = $user->wallets()->whereNull('archived_at')->get();
        $recentTransaction  = $user->transactions()
            ->with(['category', 'wallet'])
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('dashboard', compact('wallets', 'recentTransaction'));
    }

}
