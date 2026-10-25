@props(['amount'])

<span {{ $attributes->merge(['class' => 'tabular-nums']) }}>{{ $amount < 0 ? '-' : '' }}Rp&nbsp;{{ number_format(abs($amount), 0, ',', '.') }}</span>
