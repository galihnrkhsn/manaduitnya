<x-app-layout>
    <x-slot name="header">Tambah Anggaran</x-slot>

    @php
        $selected = $data['month']->copy()->startOfMonth();
        $now      = now('Asia/Jakarta')->startOfMonth();

        // Rentang pilihan: 6 bulan lalu s/d 12 bulan ke depan, selalu memuat bulan yang sedang dipilih
        $start = $now->copy()->subMonths(6)->min($selected);
        $end   = $now->copy()->addMonths(12)->max($selected);

        $months = [];
        for ($m = $start->copy(); $m->lte($end); $m->addMonth()) {
            $months[] = $m->copy();
        }
    @endphp

    <div class="rounded-xl bg-card p-5 shadow-sm border border-ink/10">
        <!-- Pilih bulan: pindah halaman supaya daftar kategori ikut menyesuaikan -->
        <div class="mb-4">
            <label for="month-picker" class="block text-sm font-medium text-ink/80">Bulan Anggaran</label>
            <select id="month-picker"
                    onchange="window.location.href = this.value"
                    class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                @foreach($months as $m)
                    <option value="{{ route('budgets.create', ['month' => $m->format('Y-m')]) }}"
                            @selected($m->format('Y-m') === $selected->format('Y-m'))>
                        {{ $m->translatedFormat('F Y') }}
                    </option>
                @endforeach
            </select>
        </div>

        <form action="{{ route('budgets.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="month" value="{{ $selected->format('Y-m') }}">

            <div>
                <label class="block text-sm font-medium text-ink/80">Kategori Pengeluaran</label>
                <select name="category_id" required class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                    <option value="">Pilih Kategori...</option>
                    @foreach($data['categories'] as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @if($data['categories']->isEmpty())
                    <p class="mt-1 text-xs text-red-500">Semua kategori sudah dianggarkan bulan ini atau belum ada kategori pengeluaran.</p>
                @endif
                <x-input-error :messages="$errors->get('category_id')" />
            </div>

            <div>
                <label class="block text-sm font-medium text-ink/80">Batas Anggaran (Rp)</label>
                <input type="number" name="amount" value="{{ old('amount') }}" required min="0"
                       class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2" placeholder="Contoh: 500000">
                <x-input-error :messages="$errors->get('amount')" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full rounded-lg bg-brand py-2.5 font-semibold text-white transition hover:bg-brand-dark"
                        @if($data['categories']->isEmpty()) disabled @endif>
                    Simpan Anggaran
                </button>
                <a href="{{ route('budgets.index', ['month' => $selected->format('Y-m')]) }}" class="mt-2 block w-full rounded-lg py-2.5 text-center font-medium text-ink/60 transition hover:bg-surface hover:text-ink">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
