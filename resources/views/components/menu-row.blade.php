@props(['href', 'label', 'hint' => null, 'icon'])

<a href="{{ $href }}" class="flex items-center gap-3 px-4 py-3 hover:bg-card">
    <svg class="h-5 w-5 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
    </svg>

    <span class="min-w-0 flex-1">
        <span class="block text-sm font-medium">{{ $label }}</span>
        @if ($hint)
            <span class="block truncate text-xs text-slate-500">{{ $hint }}</span>
        @endif
    </span>

    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
    </svg>
</a>
