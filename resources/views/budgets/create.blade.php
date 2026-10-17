<x-app-layout>
    <x-slot name="header">Tambah Anggaran</x-slot>

    <div class="rounded-xl bg-card p-5 shadow-sm border border-ink/10">
        <div class="mb-4 rounded-lg bg-surface p-3 text-sm font-medium text-ink/80">
            Anggaran untuk bulan: <span class="font-bold">{{ $data['month']->translatedFormat('F Y') }}</span>
        </div>

        <form action="{{ route('budgets.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="month" value="{{ $data['month']->format('Y-m') }}">

            <div>
                <label class="block text-sm font-medium text-ink/80">Kategori Pengeluaran</label>
                <select name="category_id" required class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2">
                    <option value="">Pilih Kategori...</option>
                    @foreach($data['categories'] as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @if($data['categories']->isEmpty())
                    <p class="mt-1 text-xs text-red-500">Semua kategori sudah dianggarkan bulan ini atau belum ada kategori pengeluaran.</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-ink/80">Batas Anggaran (Rp)</label>
                <input type="number" name="amount" required min="0" class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2" placeholder="Contoh: 500000">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full rounded-lg bg-brand py-2.5 font-semibold text-white transition hover:bg-brand-dark"
                        @if($data['categories']->isEmpty()) disabled @endif>
                    Simpan Anggaran
                </button>
                <a href="{{ route('budgets.index', ['month' => $data['month']->format('Y-m')]) }}" class="mt-2 block w-full rounded-lg py-2.5 text-center font-medium text-ink/60 transition hover:bg-surface hover:text-ink">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
