@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm font-medium text-brand-dark']) }}>
        {{ $status }}
    </div>
@endif
