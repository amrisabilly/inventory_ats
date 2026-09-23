@props(['title' => null, 'description' => null])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-border bg-surface-card shadow-panel']) }}>
    @if ($title)
        <div class="border-b border-border px-5 py-4">
            <h2 class="font-semibold text-text-primary">{{ $title }}</h2>
            @if ($description)
                <p class="mt-1 text-sm text-text-secondary">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="p-5">{{ $slot }}</div>
</section>
