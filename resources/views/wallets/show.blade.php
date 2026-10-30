<x-app-layout>
    {{-- Header --}}
    <div class="mb-6 pt-2">
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-brand-dark">&larr; Dashboard</a>
        <h1 class="mt-2 text-xl font-bold text-ink">{{ $wallet->name }}</h1>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-ink/50">Saldo</p>
        <p class="text-2xl font-bold tracking-tight text-ink">
            Rp {{ number_format($balance, 0, ',', '.') }}
        </p>
    </div>

    @if ($groups->isEmpty())
        <div class="rounded-xl border border-dashed border-ink/20 p-6 text-center">
            <p class="text-sm font-medium text-ink/60">Belum ada transaksi di dompet ini.</p>
        </div>
    @else
        @foreach ($groups as $group)
            @php
                $date = $group->first()->transaction_date;
                $label = $date->isToday() ? 'Hari ini'
                    : ($date->isYesterday() ? 'Kemarin' : $date->translatedFormat('l, d M Y'));
            @endphp

            <h2 class="mb-2 mt-6 text-xs font-bold uppercase tracking-wider text-ink/50">{{ $label }}</h2>

            <ul class="divide-y divide-ink/10 overflow-hidden rounded-xl border border-ink/10 bg-card shadow-sm">
                @foreach ($group as $t)
                    @php
                        $isTransfer    = $t->type === 3;
                        $isTransferIn  = $isTransfer && (int) $t->to_wallet_id === $wallet->id;
                        $isPlus        = $t->type === 1 || $isTransferIn;

                        if ($isTransfer) {
                            $title = $isTransferIn
                                ? 'Transfer dari ' . $t->wallet->name
                                : 'Transfer ke ' . $t->toWallet->name;
                        } else {
                            $title = $t->category->name ?? 'Tanpa kategori';
                        }
                    @endphp
                    <li>
                        <a href="{{ route('transactions.edit', $t) }}" class="flex items-center gap-4 px-4 py-3.5 transition hover:bg-surface">
                            <span class="h-3.5 w-3.5 shrink-0 rounded-full {{ $t->category ? '' : 'bg-ink/30' }}"
                                  @if ($t->category) style="background-color: {{ $t->category->color }}" @endif></span>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-ink">{{ $title }}</p>
                                @if ($t->note)
                                    <p class="mt-0.5 truncate text-xs font-medium text-ink/60">{{ $t->note }}</p>
                                @endif
                            </div>

                            <p class="shrink-0 text-sm font-bold {{ $isPlus ? 'text-green-600' : 'text-red-600' }}">
                                {{ $isPlus ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endforeach

        <div class="mt-6">{{ $transactions->links() }}</div>
    @endif
</x-app-layout>
