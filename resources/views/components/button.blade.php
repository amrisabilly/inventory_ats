@props(['variant' => 'primary', 'type' => 'button'])

@php
    $classes =
        [
            'primary' => 'bg-primary text-white hover:bg-primary-light focus:ring-primary/20',
            'secondary' => 'border border-border bg-white text-text-primary hover:bg-surface focus:ring-primary/20',
            'danger' => 'bg-danger text-white hover:bg-red-700 focus:ring-danger/20',
        ][$variant] ?? 'bg-primary text-white hover:bg-primary-light focus:ring-primary/20';
@endphp

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium shadow-sm transition focus:outline-none focus:ring-4 {$classes}"]) }}>{{ $slot }}</button>
