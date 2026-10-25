<x-app-layout>
    <div class="mb-6 flex items-center gap-2">
        <a href="{{ route('categories.index') }}" class="text-sm text-slate-500 hover:text-ink">Kategori</a>
        <span class="text-slate-300">/</span>
        <h1 class="text-xl font-semibold">{{ $category->exists ? 'Ubah kategori' : 'Tambah kategori' }}</h1>
    </div>

    <form method="POST"
          action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}"
          class="space-y-5">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        {{-- Jenis: dipilih saat membuat, terkunci saat mengubah --}}
        <div>
            <x-input-label value="Jenis" />
            @if ($category->exists)
                <p class="mt-1 text-sm">
                    {{ $category->type === \App\Models\Transaction::INCOME ? 'Pemasukan' : 'Pengeluaran' }}
                </p>
                <p class="mt-1 text-xs text-slate-500">Jenis tidak bisa diubah karena sudah terpakai di transaksi.</p>
            @else
                <div class="mt-1 grid grid-cols-2 gap-2">
                    @foreach ([\App\Models\Transaction::EXPENSE => 'Pengeluaran', \App\Models\Transaction::INCOME => 'Pemasukan'] as $value => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $value }}" class="peer sr-only"
                                   @checked((int) old('type', $category->type) === $value)>
                            <span class="block rounded-md border border-slate-300 px-3 py-2 text-center text-sm peer-checked:border-teal-700 peer-checked:bg-teal-700 peer-checked:text-white">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="name" value="Nama kategori" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $category->name)" maxlength="50" required />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-input-label value="Warna" />
            <div class="mt-2 flex flex-wrap gap-3">
                @foreach ($colors as $color)
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="{{ $color }}" class="peer sr-only"
                               @checked(old('color', $category->color) === $color)>
                        <span class="block h-8 w-8 rounded-full ring-offset-2 peer-checked:ring-2 peer-checked:ring-ink peer-focus-visible:ring-2"
                              style="background-color: {{ $color }}"></span>
                    </label>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('color')" class="mt-1" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>
            <a href="{{ route('categories.index') }}" class="text-sm text-slate-500 hover:text-ink">Batal</a>
        </div>
    </form>

    @if ($category->exists)
        <form method="POST" action="{{ route('categories.destroy', $category) }}"
              onsubmit="return confirm('Arsipkan kategori ini?')"
              class="mt-10 border-t border-slate-200 pt-6">
            @csrf
            @method('DELETE')
            <p class="text-sm text-slate-500">
                Kategori yang diarsipkan tidak muncul di pilihan, tapi transaksi lamanya tetap tercatat dan bisa dipulihkan.
            </p>
            <button class="mt-3 text-sm font-medium text-red-700 hover:underline">Arsipkan kategori</button>
        </form>
    @endif
</x-app-layout>
