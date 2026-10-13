<x-app-layout>
    <h1 class="text-xl font-semibold">Lainnya</h1>

    <div class="mt-4 divide-y divide-slate-200 rounded-md border border-slate-200 bg-surface">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm hover:bg-card">Profil</a>
        {{-- Kategori dan Dompet menyusul --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="block w-full px-4 py-3 text-left text-sm hover:bg-card">Keluar</button>
        </form>
    </div>
</x-app-layout>
