<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('wallets.index', [
            'wallets'  => $user->walletsWithBalance(),
            'archived' => $user->wallets()->whereNotNull('archived_at')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('wallets.form', ['wallet' => new Wallet(['initial_balance' => 0])]);
    }

    public function store(Request $request)
    {
        $request->user()->wallets()->create($this->validated($request));

        return redirect()->route('wallets.index')->with('status', 'Dompet ditambahkan.');
    }

    public function edit(Request $request, Wallet $wallet)
    {
        $this->own($request, $wallet);

        return view('wallets.form', compact('wallet'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        $this->own($request, $wallet);
        $wallet->update($request->toArray());

        return redirect()->route('wallets.index')->with('status', 'Dompet diperbarui.');
    }

    // "Hapus" berarti arsipkan, supaya riwayat transaksi tetap utuh
    public function destroy(Request $request, Wallet $wallet)
    {
        $this->own($request, $wallet);

        if ($request->user()->wallets()->active()->count() <= 1) {
            return back()->withErrors(['wallet' => 'Minimal harus ada satu dompet aktif.']);
        }

        $wallet->archived_at = now();
        $wallet->save();

        return redirect()->route('wallets.index')->with('status', 'Dompet diarsipkan.');
    }

    public function restore(Request $request, Wallet $wallet)
    {
        $this->own($request, $wallet);

        $wallet->archived_at = null;
        $wallet->save();

        return redirect()->route('wallets.index')->with('status', 'Dompet dipulihkan.');
    }

    public function show(Request $request, int $wallet)
    {
        $wallet = Wallet::where('user_id', $request->user()->id)->findOrFail($wallet);

        $transactions = Transaction::with(['category', 'wallet', 'toWallet'])
            ->where(function ($q) use ($wallet) {
                $q->where('wallet_id', $wallet->id)
                    ->orWhere('to_wallet_id', $wallet->id);
            })
            ->orderByDesc('transaction_date')
            ->paginate(30);

        $groups = $transactions->getCollection()
            ->groupBy(fn ($t) => $t->transaction_date->format('Y-m-d'));

        $id     = $wallet->id;
        $sum    = Transaction::where(function ($q) use ($id) {
                $q->where('wallet_id', $id)->orWhere('to_wallet_id', $id);
            })
            ->selectRaw('
                COALESCE(SUM(CASE WHEN wallet_id = ?    AND type = 1 THEN amount END), 0) AS income,
                COALESCE(SUM(CASE WHEN wallet_id = ?    AND type = 2 THEN amount END), 0) AS expense,
                COALESCE(SUM(CASE WHEN wallet_id = ?    AND type = 3 THEN amount END), 0) AS transfer_out,
                COALESCE(SUM(CASE WHEN to_wallet_id = ? AND type = 3 THEN amount END), 0) AS transfer_in
            ', [$id, $id, $id, $id])
            ->first();

        $balance = (float) ($wallet->initial_balance ?? 0)
            + (float) $sum->income
            - (float) $sum->expense
            - (float) $sum->transfer_out
            + (float) $sum->transfer_in;

        return view('wallets.show', compact('wallet', 'transactions', 'groups', 'balance'));
    }

    // Dompet milik user lain dianggap tidak ada
    private function own(Request $request, Wallet $wallet): void
    {
        abort_unless((int) $wallet->user_id === (int) $request->user()->id, 404);
    }

    private function validated(Request $request, ?Wallet $wallet = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('wallets')
                    ->where('user_id', $request->user()->id)
                    ->whereNull('archived_at')
                    ->ignore($wallet?->id),
            ],
            'initial_balance' => ['required', 'integer', 'min:0'],
        ], [
            'name.required'            => 'Nama dompet wajib diisi.',
            'name.max'                 => 'Nama dompet maksimal 50 karakter.',
            // 'name.unique'              => 'Nama dompet ini sudah dipakai.',
            'initial_balance.required' => 'Saldo awal wajib diisi (isi 0 kalau kosong).',
            'initial_balance.integer'  => 'Saldo awal harus berupa angka.',
            'initial_balance.min'      => 'Saldo awal tidak boleh negatif.',
        ]);
    }
}
