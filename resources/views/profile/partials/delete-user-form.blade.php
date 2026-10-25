<section class="mt-8 border-t border-slate-200 pt-8">
    <h2 class="text-base font-semibold text-red-700">Hapus akun</h2>
    <p class="mt-1 text-sm text-slate-500">
        Semua data (dompet, kategori, transaksi, budget) dihapus permanen dan tidak bisa dikembalikan.
    </p>

    <details class="mt-4" @if ($errors->userDeletion->isNotEmpty()) open @endif>
        <summary class="cursor-pointer text-sm font-medium text-red-700 hover:underline">
            Saya ingin menghapus akun
        </summary>

        <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4 space-y-4"
              onsubmit="return confirm('Hapus akun beserta semua datanya secara permanen?')">
            @csrf
            @method('DELETE')

            <div>
                <x-input-label for="delete_password" value="Masukkan password untuk konfirmasi" />
                <x-text-input id="delete_password" name="password" type="password"
                              class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <button type="submit"
                    class="rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800">
                Hapus akun permanen
            </button>
        </form>
    </details>
</section>
