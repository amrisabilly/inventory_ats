@props(['variant' => 'info'])

@php
    $classes =
        [
            'success' => 'bg-green-50 text-success',
            'warning' => 'bg-orange-50 text-warning',
            'danger' => 'bg-red-50 text-danger',
            'info' => 'bg-blue-50 text-info',
            'neutral' => 'bg-slate-100 text-text-secondary',
        ][$variant] ?? 'bg-slate-100 text-text-secondary';
@endphp

<span
    {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {$classes}"]) }}>{{ $slot }}</span>
