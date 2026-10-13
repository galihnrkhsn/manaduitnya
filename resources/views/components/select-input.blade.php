<select {{ $attributes->merge(['class' => 'block w-full rounded-md border-ink/20 bg-surface py-3 pl-3 pr-10 text-base text-ink focus:border-brand focus:ring-1 focus:ring-brand disabled:bg-card md:py-2.5 md:text-sm']) }}>
    {{ $slot }}
</select>
