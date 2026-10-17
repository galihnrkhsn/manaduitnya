<x-app-layout>
    <x-slot name="header">Edit Anggaran</x-slot>

    <div class="rounded-xl bg-card p-5 shadow-sm border border-ink/10">
        <div class="mb-4 flex items-center justify-between rounded-lg bg-surface p-3 text-sm font-medium">
            <div>
                <span class="text-ink/60 block text-xs">Bulan</span>
                <span class="font-bold">{{ \Carbon\Carbon::parse($budget->month)->translatedFormat('F Y') }}</span>
            </div>
            <div class="text-right">
                <span class="text-ink/60 block text-xs">Kategori</span>
                <div class="flex items-center justify-end gap-2">
                    <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $budget->category->color }}"></span>
                    <span class="font-bold">{{ $budget->category->name }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('budgets.update', $budget) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-ink/80">Ubah Batas Anggaran (Rp)</label>
                <input type="number" name="amount" value="{{ $budget->amount }}" required min="0" class="mt-1 block w-full rounded-lg border-ink/20 bg-surface px-3 py-2 text-lg font-bold">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full rounded-lg bg-brand py-2.5 font-semibold text-white transition hover:bg-brand-dark">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <hr class="my-5 border-ink/10">

        {{-- Form Hapus Anggaran --}}
        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus anggaran ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full rounded-lg border border-red-200 bg-red-50 py-2.5 font-medium text-red-600 transition hover:bg-red-100">
                Hapus Anggaran
            </button>
        </form>
    </div>
</x-app-layout>
