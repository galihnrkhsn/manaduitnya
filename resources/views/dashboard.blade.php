<x-app-layout>
    {{-- Kita sembunyikan header bawaan layout dengan membiarkan slot kosong atau tidak memanggilnya --}}

    {{-- Section 1: Header & Quotes Lucu --}}
    <div class="mb-6 pt-2"
         x-data="{
            quotes: {{ json_encode($data['quotes']) }},
            currentQuote: '',
            init() {
                // Set quote acak pertama kali
                this.currentQuote = this.quotes[Math.floor(Math.random() * this.quotes.length)];
                // Ganti quote tiap 15 detik
                setInterval(() => {
                    this.currentQuote = this.quotes[Math.floor(Math.random() * this.quotes.length)];
                }, 15000);
            }
         }">
        <h1 class="text-xl font-bold text-ink">Halo, {{ auth()->user()->name }}!</h1>
        {{-- Animasi fade tipis saat teks berganti --}}
        <p class="mt-1 min-h-[40px] text-sm font-medium text-ink/60 transition-opacity duration-500" x-text="currentQuote"></p>
    </div>

    {{-- Section 2: Total Saldo & Daftar Dompet (Horizontal Scroll) --}}
    @php
        $total = (float) $data['totalBalance'];

        // Tema warna tiap dompet, dipakai bergilir sesuai urutan
        $themes = [
            ['card' => 'bg-teal-100',   'icon' => 'bg-teal-600 text-white',   'ring' => '#0D9488'],
            ['card' => 'bg-sky-100',    'icon' => 'bg-sky-600 text-white',    'ring' => '#0284C7'],
            ['card' => 'bg-amber-100',  'icon' => 'bg-amber-500 text-white',  'ring' => '#D97706'],
            ['card' => 'bg-rose-100',   'icon' => 'bg-rose-500 text-white',   'ring' => '#E11D48'],
            ['card' => 'bg-violet-100', 'icon' => 'bg-violet-600 text-white', 'ring' => '#7C3AED'],
        ];

        $circumference = 2 * pi() * 32; // keliling lingkaran r=32, sekitar 201.06
    @endphp
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-wider text-ink/50">Total Saldo Aktif</p>
        <p class="mt-1 text-3xl font-bold tracking-tight text-ink">
            <x-money :value="$data['totalBalance']" :sign="false" class="!text-ink" />
        </p>

        <div class="-mx-4 mt-5 flex snap-x snap-mandatory scroll-px-4 gap-3 overflow-x-auto px-4 pb-2 scrollbar-hide md:mx-0 md:scroll-px-0 md:px-0">
            @foreach ($data['wallets'] as $wallet)
                @php
                    $theme   = $themes[$loop->index % count($themes)];
                    $balance = (float) $wallet->balance;

                    // Porsi saldo dompet ini terhadap total saldo aktif (0-100)
                    $pct  = ($total > 0 && $balance > 0)
                        ? min(100, round($balance / $total * 100))
                        : 0;
                    $dash = $pct / 100 * $circumference;
                @endphp

                <a href="{{ route('wallets.show', $wallet->id) }}" class="flex min-w-[290px] shrink-0 snap-start items-center justify-between gap-3 rounded-2xl p-4 shadow-sm transition active:scale-[0.98] {{ $theme['card'] }}">
                    {{-- Kiri: ikon + label, nama dompet, saldo --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full {{ $theme['icon'] }}">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                </svg>
                            </span>
                            <span class="text-xs font-medium text-ink/70">Dompet</span>
                        </div>

                        <p class="mt-3 truncate text-lg font-bold leading-tight text-ink">{{ $wallet->name }}</p>
                        <p class="mt-0.5 truncate text-xs font-semibold text-ink/60">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Kanan: ring = porsi saldo dompet ini dari total saldo --}}
                    <div class="relative h-20 w-20 shrink-0">
                        <svg class="h-full w-full -rotate-90" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="32" fill="none" stroke="white" stroke-width="8" />
                            @if ($pct > 0)
                                <circle cx="40" cy="40" r="32" fill="none" stroke="{{ $theme['ring'] }}" stroke-width="8"
                                        stroke-linecap="round"
                                        stroke-dasharray="{{ $dash }} {{ $circumference }}" />
                            @endif
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-lg font-bold leading-none text-ink">{{ $pct }}%</span>
                            <span class="mt-0.5 text-[10px] font-medium text-ink/60">dari total</span>
                        </div>
                    </div>
                </a>
            @endforeach

            {{-- Kartu Tambah Dompet --}}
            <div class="flex min-w-[100px] shrink-0 snap-start items-center justify-center rounded-2xl border-2 border-dashed border-ink/20 bg-surface p-4">
                <a class="text-xs font-semibold text-ink/50" href="{{ route('wallets.create') }}">+ Dompet</a>
            </div>
        </div>
    </div>
    {{-- Section 3: Ringkasan Bulan Ini --}}
    <div class="mb-8 grid grid-cols-2 gap-3">
        <div class="rounded-xl border border-ink/10 bg-card p-4 shadow-sm">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 4.5l-15 15m0 0h11.25m-11.25 0V8.25" /></svg>
            </div>
            <p class="mt-3 text-xs font-medium text-ink/60">Pemasukan</p>
            <p class="mt-0.5 truncate text-base font-bold text-green-600">Rp {{ number_format($data['income'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl border border-ink/10 bg-card p-4 shadow-sm">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25" /></svg>
            </div>
            <p class="mt-3 text-xs font-medium text-ink/60">Pengeluaran</p>
            <p class="mt-0.5 truncate text-base font-bold text-red-600">Rp {{ number_format($data['expense'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Section 4: Smart Alerts (Hanya tampil jika ada budget limit) --}}
    @if ($data['alerts']->isNotEmpty())
        <div class="mb-8 space-y-2">
            @foreach ($data['alerts'] as $alert)
                <div class="flex items-center gap-3 rounded-lg border-l-4 p-3 {{ $alert->is_over ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50' }}">
                    <div class="flex-1">
                        <p class="text-sm font-semibold {{ $alert->is_over ? 'text-red-700' : 'text-yellow-700' }}">
                            ⚠️ Anggaran {{ $alert->category_name }}
                        </p>
                        <p class="text-xs {{ $alert->is_over ? 'text-red-600' : 'text-yellow-600' }}">
                            {{ $alert->is_over ? 'Sudah melebihi batas!' : 'Sisa limit Rp ' . number_format($alert->remaining, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Section 5: Riwayat Transaksi Terakhir --}}
    <div>
        <div class="mb-3 flex items-end justify-between">
            <h2 class="text-sm font-bold uppercase tracking-wider text-ink/50">Transaksi Terakhir</h2>
            <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">Lihat Semua</a>
        </div>

        @if ($data['recentTransactions']->isEmpty())
            <div class="rounded-xl border border-dashed border-ink/20 p-6 text-center">
                <p class="text-sm font-medium text-ink/60">Belum ada aktivitas.</p>
            </div>
        @else
            <ul class="divide-y divide-ink/10 overflow-hidden rounded-xl border border-ink/10 bg-card shadow-sm">
                @foreach ($data['recentTransactions'] as $t)
                    <li>
                        <a href="{{ route('transactions.edit', $t) }}" class="flex items-center gap-4 px-4 py-3.5 transition hover:bg-surface">
                            <span class="h-3.5 w-3.5 shrink-0 rounded-full {{ $t->category ? '' : 'bg-ink/30' }}"
                                  @if ($t->category) style="background-color: {{ $t->category->color }}" @endif></span>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-ink">{{ $t->category->name ?? 'Transfer' }}</p>
                                <p class="truncate text-xs font-medium text-ink/60 mt-0.5">
                                    {{ $t->transaction_date->isToday() ? 'Hari ini' : $t->transaction_date->format('d M') }}
                                    {{ $t->note ? ' • '.$t->note : '' }}
                                </p>
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
        @endif
    </div>
</x-app-layout>
