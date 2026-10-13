@props(['disabled' => false])

{{-- text-base di mobile (16px) mencegah iOS zoom otomatis saat input difokus --}}
<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full rounded-md border-ink/20 bg-surface px-3 py-3 text-base text-ink placeholder:text-ink/40 focus:border-brand focus:ring-1 focus:ring-brand disabled:bg-card disabled:text-ink/50 md:py-2.5 md:text-sm']) }}>
