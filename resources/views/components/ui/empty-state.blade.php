@props([
    'icon'        => '',
    'title'       => '',
    'description' => '',
])

<div class="rounded-3xl border border-border bg-surface p-12 text-center">
    @if($icon)
        <div class="flex items-center justify-center mb-4">
            <svg class="w-16 h-16 text-muted/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>
    @endif

    @if($title)
        <p class="text-h5 font-oxanium font-semibold text-text mb-1">{{ $title }}</p>
    @endif

    @if($description)
        <p class="text-muted text-small mb-6">{{ $description }}</p>
    @endif

    {{ $slot }}
</div>
