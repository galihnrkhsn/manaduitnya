<x-app-layout>
    <x-slot name="header">Transaksi</x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-card px-4 py-3 text-sm font-medium text-brand-dark">{{ session('status') }}</div>
    @endif

    {{-- Pilih bulan --}}
    <x-month-selector :month="$month" route-name="transactions.index" />

    {{-- Ringkasan bulan --}}
    @php
        // PERBAIKAN: Menambahkan operator minus yang sebelumnya hilang
        $net = $income - $expense;
    @endphp
    <div class="mt-2 rounded-xl bg-card p-5 shadow-sm border border-ink/10">
        <p class="text-sm font-medium text-ink/60">Selisih bulan ini</p>
        <p class="mt-1 text-3xl font-bold tracking-tight">
            <x-money :value="abs($net)" :type="$net >= 0 ? 1 : 2" :sign="false" />
        </p>
        <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-ink/10 pt-5">
            <div>
                <dt class="text-sm font-medium text-ink/60">Pemasukan</dt>
                <dd class="mt-1 text-lg font-semibold"><x-money :value="$income" :type="1" :sign="false" /></dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-ink/60">Pengeluaran</dt>
                <dd class="mt-1 text-lg font-semibold"><x-money :value="$expense" :type="2" :sign="false" /></dd>
            </div>
        </dl>
    </div>

    {{-- Bungkus dengan Alpine.js: filter 'all' adalah default --}}
    <div x-data="{ filter: 'all' }">

        {{-- Filter jenis (Sekarang menggunakan tombol Alpine, bukan link a href) --}}
        <div class="-mx-4 mt-6 flex gap-2 overflow-x-auto px-4 md:mx-0 md:px-0 scrollbar-hide">
            <!-- Tombol Semua -->
            <button type="button" @click="filter = 'all'"
               :class="filter === 'all' ? 'bg-brand text-white shadow-sm' : 'bg-card text-ink/70 hover:text-ink border border-ink/10'"
               class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition">
                Semua
            </button>

            <!-- Tombol Pengeluaran (2) -->
            <button type="button" @click="filter = 2"
               :class="filter === 2 ? 'bg-brand text-white shadow-sm' : 'bg-card text-ink/70 hover:text-ink border border-ink/10'"
               class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition">
                Pengeluaran
            </button>

            <!-- Tombol Pemasukan (1) -->
            <button type="button" @click="filter = 1"
               :class="filter === 1 ? 'bg-brand text-white shadow-sm' : 'bg-card text-ink/70 hover:text-ink border border-ink/10'"
               class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition">
                Pemasukan
            </button>

            <!-- Tombol Transfer (3) -->
            <button type="button" @click="filter = 3"
               :class="filter === 3 ? 'bg-brand text-white shadow-sm' : 'bg-card text-ink/70 hover:text-ink border border-ink/10'"
               class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition">
                Transfer
            </button>
        </div>

        {{-- Daftar per hari --}}
        @forelse ($days as $items)
            @php
                $date = $items->first()->transaction_date;
                $dayNet = $items->where('type', 1)->sum('amount') - $items->where('type', 2)->sum('amount');

                // Ambil daftar tipe transaksi (1,2,3) yang ada di hari ini agar Alpine tahu kapan harus menyembunyikan seluruh header hari
                $typesInThisDay = $items->pluck('type')->unique()->values()->all();
            @endphp

            {{-- x-show pada section: Sembunyikan header hari jika filter yang dipilih tidak ada di hari tersebut --}}
            <section class="mt-8"
                     x-data="{ types: {{ json_encode($typesInThisDay) }} }"
                     x-show="filter === 'all' || types.includes(filter)"
                     x-cloak>

                <div class="mb-3 flex items-end justify-between px-1">
                    <h3 class="font-semibold text-ink/80">{{ $date->isToday() ? 'Hari ini' : $date->translatedFormat('l, j F') }}</h3>
                    @if ($dayNet !== 0)
                        {{-- Kamu bisa menyembunyikan selisih harian saat difilter agar tidak membingungkan, tapi opsional --}}
                        <div x-show="filter === 'all'">
                            <x-money :value="abs($dayNet)" :type="$dayNet > 0 ? 1 : 2" class="text-sm font-medium" />
                        </div>
                    @endif
                </div>

                <ul class="divide-y divide-ink/10 overflow-hidden rounded-xl border border-ink/10 bg-card shadow-sm">
                    @foreach ($items as $t)
                        {{-- x-show pada item: Hanya tampilkan transaksi yang sesuai dengan filter --}}
                        <li x-show="filter === 'all' || filter === {{ $t->type }}">
                            <a href="{{ route('transactions.edit', $t) }}" class="flex items-center gap-4 px-4 py-3.5 transition hover:bg-surface active:bg-surface">
                                <span class="h-3.5 w-3.5 shrink-0 rounded-full {{ $t->category ? '' : 'bg-ink/30' }}"
                                      @if ($t->category) style="background-color: {{ $t->category->color }}" @endif></span>

                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-ink">{{ $t->category->name ?? 'Transfer' }}</p>
                                    @if ($t->note)
                                        <p class="truncate text-xs font-medium text-ink/60 mt-0.5">{{ $t->note }}</p>
                                    @endif
                                </div>

                                <div class="shrink-0 text-right">
                                    <x-money :value="$t->amount" :type="$t->type" class="block text-sm font-bold" />
                                    <p class="max-w-[11rem] truncate text-[11px] font-medium text-ink/50 mt-0.5">
                                        {{ $t->type === 3 ? $t->wallet->name.' → '.$t->toWallet->name : $t->wallet->name }}
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @empty
            <div class="mt-12 flex flex-col items-center justify-center text-center">
                <!-- ... State kosong sama seperti sebelumnya ... -->
                <p class="text-ink/60 font-medium">Belum ada transaksi di bulan ini.</p>
            </div>
        @endforelse

    </div>
</x-app-layout>
