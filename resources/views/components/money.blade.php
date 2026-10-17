@props(['value', 'type' => 3, 'sign' => true])

{{-- type: 1 pemasukan (hijau, +), 2 pengeluaran (merah, -), 3 transfer (netral) --}}
@php
    $color = match ((int) $type) {
        1 => 'text-green-700',
        2 => 'text-red-600',
        default => 'text-ink/70',
    };
    $prefix = $sign ? match ((int) $type) { 1 => '+', 2 => '-', default => '' } : '';
@endphp

<span {{ $attributes->merge(['class' => "tabular-nums $color"]) }}>{{ $prefix }}Rp{{ number_format((int) $value, 0, ',', '.') }}</span>
