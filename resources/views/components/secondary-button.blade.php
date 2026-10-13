<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-md border border-ink/20 bg-surface px-4 py-3 text-sm font-semibold text-ink transition hover:bg-card focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 active:scale-[0.98] disabled:opacity-50 md:py-2.5']) }}>
    {{ $slot }}
</button>
