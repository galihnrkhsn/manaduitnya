<section class="mt-8 border-t border-slate-200 pt-8">
    <h2 class="text-base font-semibold">Ubah password</h2>
    <p class="mt-1 text-sm text-slate-500">Pakai password yang panjang dan tidak dipakai di tempat lain.</p>

    <form method="POST" action="{{ route('password.update') }}" class="mt-4 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="current_password" value="Password saat ini" />
            <x-text-input id="current_password" name="current_password" type="password"
                          class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password" value="Password baru" />
            <x-text-input id="password" name="password" type="password"
                          class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Ulangi password baru" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                          class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <x-primary-button>Ubah password</x-primary-button>
    </form>
</section>
