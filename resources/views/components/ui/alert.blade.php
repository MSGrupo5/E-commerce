@props([
    'type'        => 'success',
    'message'     => null,
    'autoDismiss' => true,
])

@php
    $config = match($type) {
        'error'   => ['border' => 'border-error/30',   'bg' => 'bg-error/10',   'icon-bg' => 'bg-error/20',   'text' => 'text-error',   'icon' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'],
        'warning' => ['border' => 'border-warning/30', 'bg' => 'bg-warning/10', 'icon-bg' => 'bg-warning/20', 'text' => 'text-warning', 'icon' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'],
        'info'    => ['border' => 'border-accent/30',  'bg' => 'bg-accent/10',  'icon-bg' => 'bg-accent/20',  'text' => 'text-accent',  'icon' => 'M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z'],
        default   => ['border' => 'border-success/30', 'bg' => 'bg-success/10', 'icon-bg' => 'bg-success/20', 'text' => 'text-success', 'icon' => 'm4.5 12.75 6 6 9-13.5'],
    };
@endphp

@if($message)
<div
    x-data="{ show: true }"
    x-show="show"
    x-cloak
    @if($autoDismiss) x-init="setTimeout(() => show = false, 4500)" @endif
    class="mb-6 rounded-2xl border {{ $config['border'] }} {{ $config['bg'] }} px-5 py-4"
    {{ $attributes }}
>
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $config['icon-bg'] }}">
            <svg class="h-5 w-5 {{ $config['text'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}" />
            </svg>
        </div>
        <p class="text-sm {{ $config['text'] }} font-medium flex-1">{{ $message }}</p>
        <button type="button" @click="show = false"
            class="ml-auto {{ $config['text'] }}/60 hover:{{ $config['text'] }} transition-colors shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
@endif
