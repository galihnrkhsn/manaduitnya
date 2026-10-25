<section>
    <h2 class="text-base font-semibold">Informasi akun</h2>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-4 space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', auth()->user()->name)" required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', auth()->user()->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <x-primary-button>Simpan</x-primary-button>
    </form>
</section>
