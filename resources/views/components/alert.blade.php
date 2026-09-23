@props(['type' => 'info'])

@php
    $classes =
        [
            'success' => 'border-green-200 bg-green-50 text-success',
            'warning' => 'border-orange-200 bg-orange-50 text-warning',
            'danger' => 'border-red-200 bg-red-50 text-danger',
            'info' => 'border-blue-200 bg-blue-50 text-info',
        ][$type] ?? 'border-blue-200 bg-blue-50 text-info';
@endphp

<div {{ $attributes->merge(['class' => "rounded-lg border px-4 py-3 text-sm {$classes}"]) }} role="alert">
    {{ $slot }}</div>
