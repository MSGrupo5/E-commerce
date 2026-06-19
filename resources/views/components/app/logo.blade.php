@props(['compact' => false])

<div class="flex items-center gap-2.5 shrink-0">
    <svg class="w-8 h-8 text-primary" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18 3L31 10.5V25.5L18 33L5 25.5V10.5L18 3Z"
              fill="currentColor" fill-opacity="0.15"
              stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
        <path d="M11 24V13L18 20L25 13V24"
              stroke="currentColor" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round"/>
    </svg>

    @unless($compact)
        <span class="font-oxanium font-bold text-base md:text-lg tracking-widest text-text">
            Marketo
        </span>
    @endunless
</div>
