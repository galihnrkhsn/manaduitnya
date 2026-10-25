<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    @php
        // Satu daftar menu, dipakai sidebar (desktop) dan bottom nav (mobile)
        $menu = [
            ['label' => 'Beranda', 'route' => 'dashboard', 'match' => ['dashboard'],
             'icon' => ['M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25']],
            ['label' => 'Transaksi', 'route' => 'transactions.index', 'match' => ['transactions.index'],
             'icon' => ['M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z']],
            ['label' => 'Budget', 'route' => 'budgets.index', 'match' => ['budgets.*'],
             'icon' => ['M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z', 'M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z']],
            ['label' => 'Lainnya', 'route' => 'more', 'match' => ['more', 'profile.*', 'wallets.*', 'categories.*'],
             'icon' => ['M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z']],
        ];
    @endphp
    <body class="min-h-screen bg-surface font-sans text-ink antialiased">
        <x-splash />

        {{-- Sidebar: desktop --}}
        <aside class="fixed inset-y-0 left-0 hidden w-60 flex-col border-r border-ink/10 bg-card p-4 md:flex">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-2 py-1">
                <x-application-logo class="h-8 w-8" alt="" />
                <span class="text-lg font-semibold">ManaDuitnya</span>
            </a>

            <a href="{{ route('transactions.create') }}"
               class="mt-6 rounded-md bg-brand px-3 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-brand-dark">
                Catat transaksi
            </a>

            <nav class="mt-6 space-y-1" aria-label="Menu utama">
                @foreach ($menu as $item)
                    @php $active = request()->routeIs(...$item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       @if ($active) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition {{ $active ? 'bg-surface text-brand-dark' : 'text-ink/70 hover:bg-surface' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            @foreach ($item['icon'] as $d)
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}" />
                            @endforeach
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button class="w-full rounded-md px-3 py-2 text-left text-sm text-ink/60 transition hover:bg-surface hover:text-ink">Keluar</button>
            </form>
        </aside>

        {{-- Konten --}}
        <div class="md:pl-60">
            <main class="mx-auto max-w-4xl px-4 py-6 pb-[calc(6rem+env(safe-area-inset-bottom))] md:px-8 md:py-8 md:pb-10">
                @isset($header)
                    <h1 class="mb-5 text-xl font-semibold md:text-2xl">{{ $header }}</h1>
                @endisset

                @if (session('status'))
                    <div class="mb-4 rounded-md border border-teal-200 bg-teal-50 px-4 py-2 text-sm text-teal-800">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        {{-- Bottom nav: mobile --}}
        <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-ink/10 bg-card md:hidden"
             style="padding-bottom: env(safe-area-inset-bottom)" aria-label="Menu utama">
            <div class="grid grid-cols-5 items-center">
                @foreach ($menu as $item)
                    {{-- Tombol Catat berada di tengah, di antara Transaksi dan Budget --}}
                    @if ($loop->index === 2)
                        <a href="{{ route('transactions.create') }}" aria-label="Catat transaksi" class="flex justify-center py-2">
                            <span class="flex h-11 w-14 items-center justify-center rounded-lg bg-brand text-white active:bg-brand-dark">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                        </a>
                    @endif

                    @php $active = request()->routeIs(...$item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       @if ($active) aria-current="page" @endif
                       class="flex flex-col items-center gap-0.5 py-2 text-[11px] font-medium {{ $active ? 'text-brand-dark' : 'text-ink/60' }}">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            @foreach ($item['icon'] as $d)
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}" />
                            @endforeach
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </nav>
    </body>
</html>
