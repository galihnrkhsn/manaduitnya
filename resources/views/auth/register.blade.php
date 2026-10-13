<x-guest-layout>
    <h1 class="text-center text-2xl font-semibold">Buat akun</h1>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" class="mt-1" type="text" name="name" :value="old('name')"
                          required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')"
                          inputmode="email" autocapitalize="none" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="mt-1" type="password" name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Ulangi password" />
            <x-text-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button class="w-full">Daftar</x-primary-button>
    </form>

    <p class="mt-6 text-center text-sm text-ink/60">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-medium text-brand-dark hover:underline">Masuk</a>
    </p>
</x-guest-layout>
