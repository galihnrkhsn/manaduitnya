<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardC extends Controller
{
    public function index(Request $request) {
        $user               = $request->user();
        $currentMonth       = now()->startOfMonth();

        $data['quotes'] = [
            "Mencatat pengeluaran, biar tahu miskinnya karena apa.",
            "Gaya elit, ekonomi sulit. Yuk catat pengeluaranmu!",
            "Kerja keras bagai quda, saldo habis entah ke mana.",
            "Uang gajian cuma numpang lewat. Mari absen ke mana dia pergi.",
            "Sudah siap melihat saldo yang bikin istighfar hari ini?",
            "Dompet teriak, hati menangis. Semangat mencatat!",
            "Info loker yang gajinya nutupin gaya hidup dong.",
            "Catat yang rajin! Walau ujung-ujungnya tetep bokek juga.",
            "Jangan lupa daratan, apalagi lupa sisa saldo.",
            "Uang bukan segalanya, tapi segalanya butuh uang."
        ];

        $data['wallets'] = $user->wallets()->whereNull('archived_at')->get()->map(function ($wallet) use ($user) {
            $income = $user->transactions()->where('wallet_id', $wallet->id)->where('type', 1)->sum('amount');
            $expense = $user->transactions()->where('wallet_id', $wallet->id)->where('type', 2)->sum('amount');
            $transferOut = $user->transactions()->where('wallet_id', $wallet->id)->where('type', 3)->sum('amount');
            $transferIn = $user->transactions()->where('to_wallet_id', $wallet->id)->where('type', 3)->sum('amount');

            $wallet->balance = $wallet->initial_balance + $income - $expense - $transferOut + $transferIn;
            return $wallet;
        });

        $data['totalBalance'] = $data['wallets']->sum('balance');

        $totals = $user->transactions()
            ->whereBetween('transaction_date', [$currentMonth, now()->endOfMonth()])
            ->selectRaw('type, sum(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $data['income'] = (int) ($totals[1] ?? 0);
        $data['expense'] = (int) ($totals[2] ?? 0);

        $budgets = $user->budgets()->with('category:id,name')->where('month', $currentMonth->format('Y-m-d'))->get();
        $data['alerts'] = collect();

        foreach ($budgets as $budget) {
            $spent = $user->transactions()
                ->where('type', 2)
                ->where('category_id', $budget->category_id)
                ->whereBetween('transaction_date', [$currentMonth, now()->endOfMonth()])
                ->sum('amount');

            $progress = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;

            if ($progress >= 80) {
                $data['alerts']->push((object)[
                    'category_name' => $budget->category->name,
                    'progress' => min(100, round($progress)),
                    'remaining' => $budget->amount - $spent,
                    'is_over' => $spent >= $budget->amount
                ]);
            }
        }

        $data['recentTransactions']  = $user->transactions()
            ->with(['category', 'wallet'])
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('dashboard', compact('data'));
    }

}
