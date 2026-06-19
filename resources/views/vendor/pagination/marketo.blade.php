@if ($paginator->hasPages())
<nav role="navigation" aria-label="Paginación" class="space-y-3">

    {{-- Flechas y números --}}
    <div class="flex items-center justify-center gap-1">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-surface text-muted opacity-40 cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               rel="prev"
               class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-surface text-muted hover:border-primary hover:text-primary transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </a>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-sm text-muted select-none">
                    &hellip;
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-sm font-semibold text-background shadow-[0_0_0_1px_rgba(108,99,255,0.4)]">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-surface text-sm font-medium text-muted hover:border-primary hover:text-primary transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               rel="next"
               class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-surface text-muted hover:border-primary hover:text-primary transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
        @else
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-surface text-muted opacity-40 cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </span>
        @endif

    </div>

    {{-- Página X de Y --}}
    <p class="text-center text-xs text-muted">
        Página <span class="font-medium text-text">{{ $paginator->currentPage() }}</span>
        de
        <span class="font-medium text-text">{{ $paginator->lastPage() }}</span>
    </p>

</nav>
@endif
