@props(['value', 'type' => null, 'sign' => true])

@php
    // Menentukan warna berdasarkan tipe
    $colorClass = match((int) $type) {
        1 => 'text-green-600',
        2 => 'text-red-600',
        3 => 'text-brand',
        default => 'text-ink'
    };

    // Menentukan tanda plus/minus
    $prefix = '';
    if ($sign) {
        if ($type == 1) $prefix = '+';
        if ($type == 2) $prefix = '-';
    }
@endphp

<span {{ $attributes->merge(['class' => $colorClass]) }}>
    {{ $prefix }}Rp {{ number_format((float)$value, 0, ',', '.') }}
</span>
