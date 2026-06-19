@props([
    'label' => '',
    'value' => 0,
    'icon'  => '',
    'color' => 'primary',
])

<div class="rounded-3xl border border-border bg-surface p-6 flex items-center gap-4">
    <div class="h-12 w-12 rounded-2xl bg-{{ $color }}/10 border border-{{ $color }}/20 flex items-center justify-center shrink-0">
        <svg class="w-6 h-6 text-{{ $color }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    </div>
    <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">{{ $label }}</p>
        <p class="text-h3 font-bold text-text font-oxanium">{{ $value }}</p>
    </div>
</div>
