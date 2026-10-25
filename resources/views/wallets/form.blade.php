<x-app-layout>
    <div class="mb-6 flex items-center gap-2">
        <a href="{{ route('wallets.index') }}" class="text-sm text-slate-500 hover:text-ink">Dompet</a>
        <span class="text-slate-300">/</span>
        <h1 class="text-xl font-semibold">{{ $wallet->exists ? 'Ubah dompet' : 'Tambah dompet' }}</h1>
    </div>

    <form method="POST"
          action="{{ $wallet->exists ? route('wallets.update', $wallet) : route('wallets.store') }}"
          class="space-y-5">
        @csrf
        @if ($wallet->exists)
            @method('PUT')
        @endif

        <div>
            <x-input-label for="name" value="Nama dompet" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $wallet->name)" maxlength="50" required />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="initial_balance" value="Saldo awal" />
            <div class="mt-1">
                <x-money-input name="initial_balance" :value="$wallet->initial_balance" />
            </div>
            <p class="mt-1 text-xs text-slate-500">
                Saldo saat kamu mulai mencatat. Transaksi akan menambah atau mengurangi dari angka ini.
            </p>
            <x-input-error :messages="$errors->get('initial_balance')" class="mt-1" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>
            <a href="{{ route('wallets.index') }}" class="text-sm text-slate-500 hover:text-ink">Batal</a>
        </div>
    </form>

    @if ($wallet->exists)
        <form method="POST" action="{{ route('wallets.destroy', $wallet) }}"
              onsubmit="return confirm('Arsipkan dompet ini?')"
              class="mt-10 border-t border-slate-200 pt-6">
            @csrf
            @method('DELETE')
            <p class="text-sm text-slate-500">
                Dompet yang diarsipkan tidak muncul di pilihan, tapi riwayat transaksinya tetap ada dan bisa dipulihkan.
            </p>
            <x-input-error :messages="$errors->get('wallet')" class="mt-2" />
            <button class="mt-3 text-sm font-medium text-red-700 hover:underline">Arsipkan dompet</button>
        </form>
    @endif
</x-app-layout>
