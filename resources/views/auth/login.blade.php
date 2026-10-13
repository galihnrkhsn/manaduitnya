<x-guest-layout>
    <h1 class="text-center text-2xl font-semibold">Masuk</h1>
    <p class="mt-1 text-center text-sm text-ink/60">
        Mencatat pengeluaran bulanan, biar tahu miskinnya karena apa.
    </p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf
        {{-- Tetap login sampai tekan Keluar --}}
        <input type="hidden" name="remember" value="1">

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')"
                          placeholder="nama@email.com" inputmode="email" autocapitalize="none"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="Password" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand-dark hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="mt-1" type="password" name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button class="w-full">Masuk</x-primary-button>
    </form>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-ink/60">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-brand-dark hover:underline">Daftar</a>
        </p>
    @endif
</x-guest-layout>
