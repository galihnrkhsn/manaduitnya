<x-app-layout>
    <x-slot name="header">Transaksi</x-slot>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-card px-4 py-3 text-sm font-medium text-brand-dark">{{ session('status') }}</div>
    @endif

    {{-- Pilih bulan --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('transactions.index', ['month' => $month->copy()->subMonth()->format('Y-m'), 'type' => $type]) }}"
           aria-label="Bulan sebelumnya"
           class="flex h-11 w-11 items-center justify-center rounded-md text-ink/70 hover:bg-card">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>
        <h2 class="text-base font-semibold">{{ $month->translatedFormat('F Y') }}</h2>
        <a href="{{ route('transactions.index', ['month' => $month->copy()->addMonth()->format('Y-m'), 'type' => $type]) }}"
           aria-label="Bulan berikutnya"
           class="flex h-11 w-11 items-center justify-center rounded-md text-ink/70 hover:bg-card">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    </div>

    {{-- Ringkasan bulan --}}
    @php $net = $income - $expense; @endphp
    <div class="mt-2 rounded-lg bg-card p-4">
        <p class="text-sm text-ink/60">Selisih bulan ini</p>
        <p class="mt-0.5 text-3xl font-semibold">
            <x-money :value="abs($net)" :type="$net >= 0 ? 1 : 2" :sign="false" />
        </p>
        <dl class="mt-4 grid grid-cols-2 gap-4 border-t border-ink/10 pt-4">
            <div>
                <dt class="text-sm text-ink/60">Pemasukan</dt>
                <dd class="mt-0.5 font-semibold"><x-money :value="$income" :type="1" :sign="false" /></dd>
            </div>
            <div>
                <dt class="text-sm text-ink/60">Pengeluaran</dt>
                <dd class="mt-0.5 font-semibold"><x-money :value="$expense" :type="2" :sign="false" /></dd>
            </div>
        </dl>
    </div>

    {{-- Filter jenis --}}
    <div class="-mx-4 mt-5 flex gap-2 overflow-x-auto px-4 md:mx-0 md:px-0">
        @foreach ([['Semua', null], ['Pengeluaran', 2], ['Pemasukan', 1], ['Transfer', 3]] as [$label, $value])
            <a href="{{ route('transactions.index', ['month' => $month->format('Y-m'), 'type' => $value]) }}"
               @if ($type === $value) aria-current="true" @endif
               class="shrink-0 rounded-full px-4 py-2 text-sm font-medium {{ $type === $value ? 'bg-ink text-surface' : 'bg-card text-ink/70 hover:text-ink' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Daftar per hari --}}
    @forelse ($days as $items)
        @php
            $date = $items->first()->transaction_date;
            $dayNet = $items->where('type', 1)->sum('amount') - $items->where('type', 2)->sum('amount');
        @endphp
        <section class="mt-6">
            <div class="mb-2 flex items-baseline justify-between px-1 text-sm text-ink/60">
                <h3 class="font-medium">{{ $date->isToday() ? 'Hari ini' : $date->translatedFormat('l, j F') }}</h3>
                @if ($dayNet !== 0)
                    <x-money :value="abs($dayNet)" :type="$dayNet > 0 ? 1 : 2" />
                @endif
            </div>

            <ul class="divide-y divide-ink/10 overflow-hidden rounded-lg border border-ink/10">
                @foreach ($items as $t)
                    <li>
                        <a href="{{ route('transactions.edit', $t) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-card">
                            <span class="h-3 w-3 shrink-0 rounded-full {{ $t->category ? '' : 'bg-ink/30' }}"
                                  @if ($t->category) style="background-color: {{ $t->category->color }}" @endif></span>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium">{{ $t->category->name ?? 'Transfer' }}</p>
                                @if ($t->note)
                                    <p class="truncate text-sm text-ink/60">{{ $t->note }}</p>
                                @endif
                            </div>

                            <div class="shrink-0 text-right">
                                <x-money :value="$t->amount" :type="$t->type" class="text-sm font-semibold" />
                                <p class="max-w-[11rem] truncate text-xs text-ink/50">
                                    {{ $t->type === 3 ? $t->wallet->name.' ke '.$t->toWallet->name : $t->wallet->name }}
                                </p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @empty
        <div class="mt-10 text-center">
            <p class="text-ink/60">Belum ada transaksi di bulan ini.</p>
            <a href="{{ route('transactions.create') }}"
               class="mt-4 inline-flex rounded-md bg-brand px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-dark">
                Catat transaksi
            </a>
        </div>
    @endforelse
</x-app-layout>
