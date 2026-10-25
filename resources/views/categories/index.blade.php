<x-app-layout>
    <h1 class="text-xl font-semibold">Kategori</h1>

    @foreach ($sections as $section)
        <div class="mb-2 mt-6 flex items-center justify-between">
            <h2 class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $section['title'] }}</h2>
            <a href="{{ route('categories.create', ['jenis' => $section['type']]) }}"
               class="text-sm font-medium text-teal-700 hover:underline">Tambah</a>
        </div>

        <div class="divide-y divide-slate-200 overflow-hidden rounded-md border border-slate-200 bg-surface">
            @forelse ($section['items'] as $category)
                <a href="{{ route('categories.edit', $category) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-card">
                    <span class="h-3 w-3 shrink-0 rounded-full" style="background-color: {{ $category->color }}"></span>
                    <span class="text-sm font-medium">{{ $category->name }}</span>
                </a>
            @empty
                <p class="px-4 py-3 text-sm text-slate-500">Belum ada kategori.</p>
            @endforelse
        </div>
    @endforeach

    @if ($archived->isNotEmpty())
        <details class="mt-8">
            <summary class="cursor-pointer text-sm text-slate-500">Kategori diarsipkan ({{ $archived->count() }})</summary>
            <div class="mt-2 divide-y divide-slate-200 overflow-hidden rounded-md border border-slate-200">
                @foreach ($archived as $category)
                    <form method="POST" action="{{ route('categories.restore', $category) }}"
                          class="flex items-center justify-between px-4 py-3">
                        @csrf
                        @method('PATCH')
                        <span class="text-sm text-slate-500">
                            {{ $category->name }}
                            ({{ $category->type === \App\Models\Transaction::INCOME ? 'pemasukan' : 'pengeluaran' }})
                        </span>
                        <button class="text-sm font-medium text-teal-700 hover:underline">Pulihkan</button>
                    </form>
                @endforeach
            </div>
        </details>
    @endif
</x-app-layout>
