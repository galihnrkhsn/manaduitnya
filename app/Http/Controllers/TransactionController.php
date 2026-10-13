<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $this->parseMonth($request->query('month'));
        $type = in_array((int) $request->query('type'), [1, 2, 3], true) ? (int) $request->query('type') : null;

        $inMonth = Transaction::where('user_id', $request->user()->id)
            ->whereBetween('transaction_date', [$month, $month->copy()->endOfMonth()]);

        // Ringkasan bulan ini (tidak terpengaruh filter jenis; transfer tidak dihitung)
        $totals = (clone $inMonth)->selectRaw('type, sum(amount) as total')->groupBy('type')->pluck('total', 'type');
        $income = (int) ($totals[Transaction::INCOME] ?? 0);
        $expense = (int) ($totals[Transaction::EXPENSE] ?? 0);

        $days = $inMonth
            ->when($type, fn ($q) => $q->where('type', $type))
            ->with(['wallet:id,name', 'toWallet:id,name', 'category:id,name,color'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn ($t) => $t->transaction_date->toDateString());

        return view('transaction.index', compact('month', 'type', 'income', 'expense', 'days'));
    }

    public function create(Request $request)
    {
        $transaction = new Transaction([
            'type' => in_array((int) $request->query('type'), [1, 2, 3], true) ? (int) $request->query('type') : Transaction::EXPENSE,
            'transaction_date' => now(),
            // dompet yang terakhir dipakai
            'wallet_id' => Transaction::where('user_id', $request->user()->id)->latest()->value('wallet_id'),
        ]);

        return view('transaction.create', $this->formData($request, $transaction));
    }

    public function store(TransactionRequest $request)
    {
        $transaction = new Transaction($request->payload());
        $transaction->user_id = $request->user()->id;
        $transaction->save();

        return $this->backToList($transaction, 'Transaksi disimpan.');
    }

    public function edit(Request $request, Transaction $transaction)
    {
        $this->own($request, $transaction);

        return view('transaction.edit', $this->formData($request, $transaction));
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $this->own($request, $transaction);
        $transaction->update($request->payload());

        return $this->backToList($transaction, 'Transaksi diperbarui.');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $this->own($request, $transaction);
        $transaction->delete();

        return $this->backToList($transaction, 'Transaksi dihapus.');
    }

    private function own(Request $request, Transaction $transaction): void
    {
        abort_unless((int) $transaction->user_id === (int) $request->user()->id, 404);
    }

    /** Kembali ke daftar di bulan transaksi tadi, supaya hasilnya langsung terlihat. */
    private function backToList(Transaction $transaction, string $message)
    {
        return redirect()
            ->route('transaction.index', ['month' => $transaction->transaction_date->format('Y-m')])
            ->with('status', $message);
    }

    private function parseMonth(?string $value): Carbon
    {
        try {
            return Carbon::createFromFormat('!Y-m', $value ?? now()->format('Y-m'));
        } catch (\Throwable) {
            return now()->startOfMonth();
        }
    }

    /** Dompet & kategori untuk form. Yang diarsipkan disembunyikan, kecuali masih dipakai transaksi ini. */
    private function formData(Request $request, Transaction $transaction): array
    {
        $userId = $request->user()->id;

        $wallets = Wallet::where('user_id', $userId)
            ->where(fn ($q) => $q->whereNull('archived_at')
                ->orWhereIn('id', array_filter([$transaction->wallet_id, $transaction->to_wallet_id])))
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::where('user_id', $userId)
            ->where(fn ($q) => $q->whereNull('archived_at')->orWhere('id', $transaction->category_id))
            ->orderBy('name')
            ->get(['id', 'name', 'type'])
            ->groupBy('type');

        return compact('transaction', 'wallets', 'categories');
    }
}
