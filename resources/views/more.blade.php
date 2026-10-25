<x-app-layout>
    <h1 class="text-xl font-semibold">Lainnya</h1>

    <h2 class="mb-2 mt-6 text-xs font-medium uppercase tracking-wide text-slate-500">Kelola</h2>
    <div class="divide-y divide-slate-200 overflow-hidden rounded-md border border-slate-200 bg-surface">
        <x-menu-row
            :href="route('wallets.index')"
            label="Dompet"
            hint="Tunai, rekening bank, e-wallet"
            icon="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />

        <x-menu-row
            :href="route('categories.index')"
            label="Kategori"
            hint="Kategori pemasukan dan pengeluaran"
            icon="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z" />
    </div>

    <h2 class="mb-2 mt-6 text-xs font-medium uppercase tracking-wide text-slate-500">Akun</h2>
    <div class="divide-y divide-slate-200 overflow-hidden rounded-md border border-slate-200 bg-surface">
        <x-menu-row
            :href="route('profile.edit')"
            label="Profil"
            :hint="auth()->user()->email"
            icon="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />

        <form method="POST" action="{{ route('logout') }}"
              onsubmit="return confirm('Keluar dari ManaDuitnya?')">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left hover:bg-card">
                <svg class="h-5 w-5 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                <span class="text-sm font-medium">Keluar</span>
            </button>
        </form>
    </div>

    <p class="mt-8 text-center text-xs text-slate-400">
        ManaDuitnya<br>
        Mencatat pengeluaran bulanan, biar tahu miskinnya karena apa.
    </p>
</x-app-layout>
