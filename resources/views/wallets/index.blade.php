<x-app-layout>
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">Dompet</h1>
        <a href="{{ route('wallets.create') }}"
           class="rounded-md bg-teal-700 px-3 py-2 text-sm font-semibold text-white hover:bg-teal-800">Tambah</a>
    </div>

    <div class="mt-4 rounded-md border border-slate-200 bg-card px-4 py-3">
        <p class="text-xs text-slate-500">Total saldo</p>
        <x-rupiah :amount="$wallets->sum('current_balance')" class="text-2xl font-semibold" />
    </div>

    <div class="mt-4 divide-y divide-slate-200 overflow-hidden rounded-md border border-slate-200 bg-surface">
        @foreach ($wallets as $wallet)
            <a href="{{ route('wallets.edit', $wallet) }}" class="flex items-center justify-between px-4 py-3 hover:bg-card">
                <span class="text-sm font-medium">{{ $wallet->name }}</span>
                <x-rupiah :amount="$wallet->current_balance" class="text-sm" />
            </a>
        @endforeach
    </div>

    @if ($archived->isNotEmpty())
        <details class="mt-6">
            <summary class="cursor-pointer text-sm text-slate-500">Dompet diarsipkan ({{ $archived->count() }})</summary>
            <div class="mt-2 divide-y divide-slate-200 overflow-hidden rounded-md border border-slate-200">
                @foreach ($archived as $wallet)
                    <form method="POST" action="{{ route('wallets.restore', $wallet) }}"
                          class="flex items-center justify-between px-4 py-3">
                        @csrf
                        @method('PATCH')
                        <span class="text-sm text-slate-500">{{ $wallet->name }}</span>
                        <button class="text-sm font-medium text-teal-700 hover:underline">Pulihkan</button>
                    </form>
                @endforeach
            </div>
        </details>
    @endif
</x-app-layout>
