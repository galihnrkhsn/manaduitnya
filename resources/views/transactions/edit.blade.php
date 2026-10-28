<x-app-layout>
    <x-slot name="header">Ubah Transaksi</x-slot>

    <div x-data="{ type: {{ (int) old('type', $transaction->type) }} }" class="rounded-xl bg-card p-5 shadow-sm border border-ink/10">
        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Pilihan Tipe Transaksi -->
            <div class="flex gap-2 rounded-lg bg-surface p-1">
                <button type="button" @click="type = 2" :class="type === 2 ? 'bg-white shadow text-red-600' : 'text-ink/60'" class="flex-1 rounded-md py-1.5 text-sm font-medium transition">Pengeluaran</button>
                <button type="button" @click="type = 1" :class="type === 1 ? 'bg-white shadow text-green-600' : 'text-ink/60'" class="flex-1 rounded-md py-1.5 text-sm font-medium transition">Pemasukan</button>
                <button type="button" @click="type = 3" :class="type === 3 ? 'bg-white shadow text-brand' : 'text-ink/60'" class="flex-1 rounded-md py-1.5 text-sm font-medium transition">Transfer</button>
            </div>

            <input type="hidden" name="type" x-model="type">
            <x-input-error :messages="$errors->get('type')" />

            <!-- Nominal -->
            <div>
                <label class="block text-sm font-medium text-ink/80">Nominal</label>
                <input type="number" name="amount" value="{{ old('amount', (int) $transaction->amount) }}" required class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                <x-input-error :messages="$errors->get('amount')" />
            </div>

            <!-- Dompet Asal -->
            <div>
                <label class="block text-sm font-medium text-ink/80" x-text="type === 3 ? 'Dari Dompet' : 'Dompet'"></label>
                <select name="wallet_id" required class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}" @selected((int) old('wallet_id', $transaction->wallet_id) === $wallet->id)>{{ $wallet->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('wallet_id')" />
            </div>

            <!-- Dompet Tujuan (Hanya tampil jika tipe = 3 / Transfer) -->
            <div x-show="type === 3" x-cloak>
                <label class="block text-sm font-medium text-ink/80">Ke Dompet</label>
                <select name="to_wallet_id" :disabled="type !== 3" class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                    <option value="">Pilih Dompet Tujuan...</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}" @selected((int) old('to_wallet_id', $transaction->to_wallet_id) === $wallet->id)>{{ $wallet->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('to_wallet_id')" />
            </div>

            <!-- Kategori (Sembunyikan jika Transfer) -->
            <div x-show="type !== 3" x-cloak>
                <label class="block text-sm font-medium text-ink/80">Kategori</label>

                @foreach($categories as $catType => $cats)
                    <select name="category_id"
                            x-show="type === {{ $catType }}"
                            :disabled="type !== {{ $catType }}"
                            x-cloak
                            class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                        <option value="">Pilih Kategori...</option>
                        @foreach($cats as $cat)
                            <option value="{{ $cat->id }}" @selected((int) old('category_id', $transaction->category_id) === $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                @endforeach
                <x-input-error :messages="$errors->get('category_id')" />
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-medium text-ink/80">Catatan</label>
                <input type="text" name="note" value="{{ old('note', $transaction->note) }}" class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                <x-input-error :messages="$errors->get('note')" />
            </div>

            <!-- Tanggal Transaksi -->
            <div>
                <label class="block text-sm font-medium text-ink/80">Tanggal</label>
                <input type="datetime-local" name="transaction_date"
                       value="{{ old('transaction_date', \Illuminate\Support\Carbon::parse($transaction->transaction_date)->format('Y-m-d\TH:i')) }}"
                       required class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                <x-input-error :messages="$errors->get('transaction_date')" />
            </div>

            <div class="flex gap-2 pt-2">
                <a href="{{ url()->previous() }}" class="flex-1 rounded-lg border border-ink/20 py-2.5 text-center font-semibold text-ink/70">Batal</a>
                <button type="submit" class="flex-1 rounded-lg bg-brand py-2.5 font-semibold text-white">Simpan Perubahan</button>
            </div>
        </form>

        <!-- Hapus (form terpisah, tidak boleh nested) -->
        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
              onsubmit="return confirm('Hapus transaksi ini?')" class="mt-3">
            @csrf
            @method('DELETE')
            <x-danger-button class="w-full">Hapus Transaksi</x-danger-button>
        </form>
    </div>
</x-app-layout>
