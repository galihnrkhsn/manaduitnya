<x-app-layout>
    <x-slot name="header">Catat Transaksi</x-slot>

    <!-- x-data mendefinisikan state Alpine.js. Misal: 2 = Pengeluaran, 1 = Pemasukan, 3 = Transfer -->
    <div x-data="{ type: 2 }" class="rounded-xl bg-card p-5 shadow-sm border border-ink/10">
        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Pilihan Tipe Transaksi -->
            <div class="flex gap-2 rounded-lg bg-surface p-1">
                <button type="button" @click="type = 2" :class="type === 2 ? 'bg-white shadow text-red-600' : 'text-ink/60'" class="flex-1 rounded-md py-1.5 text-sm font-medium transition">Pengeluaran</button>
                <button type="button" @click="type = 1" :class="type === 1 ? 'bg-white shadow text-green-600' : 'text-ink/60'" class="flex-1 rounded-md py-1.5 text-sm font-medium transition">Pemasukan</button>
                <button type="button" @click="type = 3" :class="type === 3 ? 'bg-white shadow text-brand' : 'text-ink/60'" class="flex-1 rounded-md py-1.5 text-sm font-medium transition">Transfer</button>
            </div>

            <!-- Input Type Hidden agar dikirim ke server -->
            <input type="hidden" name="type" x-model="type">

            <!-- Nominal -->
            <div>
                <label class="block text-sm font-medium text-ink/80">Nominal</label>
                <input type="number" name="amount" required class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
            </div>

            <!-- Dompet Asal -->
            <div>
                <label class="block text-sm font-medium text-ink/80" x-text="type === 3 ? 'Dari Dompet' : 'Dompet'"></label>
                <select name="wallet_id" required class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dompet Tujuan (Hanya tampil jika tipe = 3 / Transfer) -->
            <div x-show="type === 3" x-cloak>
                <label class="block text-sm font-medium text-ink/80">Ke Dompet</label>
                <select name="to_wallet_id" class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                    <option value="">Pilih Dompet Tujuan...</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                    @endforeach
                </select>
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
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                @endforeach
            </div>

            <!-- Catatan / Note -->
            <div>
                <label class="block text-sm font-medium text-ink/80">Catatan</label>
                <input type="text" name="note" class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
            </div>

            <!-- Tanggal Transaksi -->
            <div>
                <label class="block text-sm font-medium text-ink/80">Tanggal</label>
                <input type="datetime-local" name="transaction_date" value="{{ now()->format('Y-m-d\TH:i') }}" required class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
            </div>

            <button type="submit" class="w-full rounded-lg bg-brand py-2.5 font-semibold text-white mt-4">Simpan Transaksi</button>
        </form>
    </div>
</x-app-layout>
