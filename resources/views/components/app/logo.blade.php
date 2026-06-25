@props(['compact' => false, 'large' => false])

<div class="flex items-center justify-center shrink-0">
    <img
        src="{{ Vite::asset('resources/images/marketo_logo_final.svg') }}"
        alt="Marketo"
        class="{{ $compact ? 'h-9' : ($large ? 'h-20 md:h-24' : 'h-[54px] md:h-[62px]') }} w-auto"
    >
</div>
