@props(['month', 'routeName'])

@php
    $prevMonthUrl = route($routeName, ['month' => $month->copy()->subMonth()->format('Y-m')]);
    $nextMonthUrl = route($routeName, ['month' => $month->copy()->addMonth()->format('Y-m')]);
@endphp

<div class="flex items-center justify-between">
    <a href="{{ $prevMonthUrl }}"
       aria-label="Bulan sebelumnya"
       class="flex h-11 w-11 items-center justify-center rounded-md text-ink/70 transition hover:bg-card">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </a>
    <h2 class="text-base font-semibold">{{ $month->translatedFormat('F Y') }}</h2>
    <a href="{{ $nextMonthUrl }}"
       aria-label="Bulan berikutnya"
       class="flex h-11 w-11 items-center justify-center rounded-md text-ink/70 transition hover:bg-card">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </a>
</div>
