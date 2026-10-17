<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;

class BudgetC extends Controller
{
    public function index(Request $request) {
        $user           = $request->user();
        $data['month']  = parseMonth($request->query('month'));
        $dbMonth        = $data['month']->format('Y-m-d');

        $data['budgets'] = Budget::with('category:id,name,color')
            ->where('user_id', $user->id)
            ->where('month', $dbMonth)
            ->get();

        $expensesByCategory = Transaction::where('user_id', $user->id)
            ->where('type', 2)
            ->whereBetween('transaction_date', [$data['month'], $data['month']->copy()->endOfMonth()])
            ->selectRaw(
                'category_id, SUM(amount) as total_spent'
            )
            ->groupBy('category_id')
            ->pluck('total_spent', 'category_id');

        $data['totalBudget']    = 0;
        $data['totalSpent']     = 0;

        foreach($data['budgets'] as $b) {
            $spent      = $expensesByCategory[$b->category_id] ?? 0;
            $b->spent   = $spent;

            $b->progress = $b->amount > 0 ? min(100, round(($spent / $b->amount) * 100)) : 0;

            $data['totalBudget'] += $b->amount;
            $data['totalSpent'] += $spent;
        }

        $data['globalProgress'] = $data['totalBudget'] > 0 ? min(100, round(($data['totalSpent'] / $data['totalBudget']) * 100)) : 0;
        $data['remaining']      = $data['totalBudget'] - $data['totalSpent'];

        return view('budgets.index', compact('data'));
    }

    public function create(Request $request) {
        $user           = auth()->user();
        $data['month']  = parseMonth($request->query('month'));
        $dbMonth        = $data['month']->format('Y-m-d');

        $existingCategoryIds = Budget::where('user_id', $user->id)
            ->where('month', $dbMonth)
            ->pluck('category_id');

        $data['categories'] = $user->categories()
            ->where('type', 2)
            ->whereNull('archived_at')
            ->whereNotIn('id', $existingCategoryIds)
            ->get();

        return view('budgets.create', compact('data'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'category_id'   => 'bail|required|exists:categories,id',
            'amount'        => 'bail|required|integer|min:0',
            'month'         => 'bail|required|date_format:Y-m',
        ]);

        $dbMonth = Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth()->format('Y-m-d');

        Budget::create([
            'user_id'       => $request->user()->id,
            'category_id'   => $validated['category_id'],
            'amount'        => $validated['amount'],
            'month'         => $dbMonth
        ]);

        return redirect()->route('budgets.index', ['month' => $validated['month']])->with('status', 'Anggaran Baru Berhasil Disimpan');
    }

    public function edit(Budget $budget) {
        if ($budget->user_id !== auth()->user()->id) abort(403);

        $budget->load('category');
        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget) {
        if ($budget->user_id !== $request->user()->id) abort(403);

        $validated = $request->validate([
            'amount'    => 'bail|required|integer|min:0'
        ]);

        $budget->update([
            'amount'    => $validated['amount']
        ]);

        return redirect()->route('budgets.index', compact(['month' => Carbon::parse($budget->month)->format('Y-m-d')]))
            ->with('status', 'Anggaran Berhasil diperbarui');
    }

    public function destroy(Budget $budget) {
        if ($budget->user_id !== auth()->user()->id) abort(403);

        $monthFormat = Carbon::parse($budget->month)->format('Y-m');
        $budget->delete();

        return redirect()->route('budgets.index', compact(['month' => $monthFormat]))->with('status', 'Anggaran Berhasil dihapus');
    }
}
