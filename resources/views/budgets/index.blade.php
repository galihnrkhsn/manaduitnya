<x-app-layout>
    <x-slot name="header">Anggaran Bulanan</x-slot>

    {{-- Gunakan Komponen Pemilih Bulan --}}
    <x-month-selector :month="$data['month']" route-name="budgets.index" />

    {{-- Hero Card: Ringkasan Global --}}
    <div class="mt-4 rounded-xl bg-card p-5 shadow-sm border border-ink/10">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-ink/60">Sisa Anggaran</p>
            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $data['remaining'] < 0 ? 'bg-red-100 text-red-700' : 'bg-surface text-ink/70' }}">
                {{ $data['globalProgress'] }}% Terpakai
            </span>
        </div>

        <p class="mt-1 text-3xl font-bold tracking-tight">
            <x-money :value="abs($data['remaining'])" :type="$data['remaining'] >= 0 ? 1 : 2" :sign="false" />
        </p>

        {{-- Progress Bar Global --}}
        <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-surface">
            <div class="h-full rounded-full transition-all duration-500 {{ $data['globalProgress'] > 90 ? 'bg-red-500' : ($data['globalProgress'] > 70 ? 'bg-yellow-500' : 'bg-brand') }}"
                 style="width: {{ $data['globalProgress'] }}%"></div>
        </div>

        <div class="mt-3 flex justify-between text-xs font-medium text-ink/60">
            <p>Terpakai: Rp {{ number_format($data['totalSpent'], 0, ',', '.') }}</p>
            <p>Total: Rp {{ number_format($data['totalBudget'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Daftar Kategori --}}
    <div class="mt-6 flex items-center justify-between">
        <h3 class="font-semibold text-ink/80">Anggaran Kategori</h3>
        <a href="{{ route('budgets.create') }}" class="text-sm font-semibold text-brand hover:text-brand-dark">
            + Tambah
        </a>
    </div>

    @if($data['budgets']->isEmpty())
        <div class="mt-4 flex flex-col items-center justify-center rounded-xl border border-dashed border-ink/20 p-8 text-center">
            <p class="text-ink/60 font-medium text-sm">Belum ada anggaran yang dibuat bulan ini.</p>
        </div>
    @else
        <ul class="mt-4 space-y-3">
            @foreach($data['budgets'] as $budget)
                @php
                    // Logika warna progress bar
                    $barColor = $budget->progress > 90 ? 'bg-red-500' : ($budget->progress > 70 ? 'bg-yellow-500' : 'bg-brand');
                    // Status teks
                    $statusText = $budget->spent > $budget->amount ? 'Overbudget' : 'Sisa Rp ' . number_format($budget->amount - $budget->spent, 0, ',', '.');
                    $statusColor = $budget->spent > $budget->amount ? 'text-red-500' : 'text-ink/50';
                @endphp

                <li>
                    {{-- Klik card untuk mengedit anggaran --}}
                    <a href="{{ route('budgets.edit', $budget->id) }}" class="block rounded-xl border border-ink/10 bg-card p-4 shadow-sm transition hover:bg-surface">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="h-3 w-3 shrink-0 rounded-full" style="background-color: {{ $budget->category->color }}"></span>
                                <span class="font-semibold text-sm">{{ $budget->category->name }}</span>
                            </div>
                            <span class="text-xs font-semibold {{ $statusColor }}">{{ $statusText }}</span>
                        </div>

                        {{-- Info Nominal --}}
                        <div class="mt-2.5 flex items-end justify-between text-sm">
                            <p class="font-bold">Rp {{ number_format($budget->spent, 0, ',', '.') }}</p>
                            <p class="text-xs text-ink/50 font-medium">dari Rp {{ number_format($budget->amount, 0, ',', '.') }}</p>
                        </div>

                        {{-- Progress Bar Individual --}}
                        <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-surface">
                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $budget->progress }}%"></div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</x-app-layout>
