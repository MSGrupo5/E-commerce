{{-- resources/views/components/ui/loader.blade.php --}}
{{-- Overlay con spinner que se muestra al navegar entre secciones/vistas (enlaces internos y envío de forms) --}}
<div
    x-data="{ loading: false }"
    @click.window.capture="
        const link = $event.target.closest('a[href]');
        if (! link || link.target === '_blank' || link.hasAttribute('download')) return;
        if ($event.metaKey || $event.ctrlKey || $event.shiftKey || $event.altKey) return;
        const href = link.getAttribute('href');
        if (! href || href.startsWith('#') || href.startsWith('javascript:')) return;
        if (link.origin !== window.location.origin) return;
        loading = true;
    "
    @submit.window.capture="loading = true"
    @pageshow.window="loading = false"
    x-show="loading"
    x-cloak
    x-transition.opacity.duration.150ms
    class="fixed inset-0 z-[100] flex items-center justify-center bg-background/70 backdrop-blur-sm"
>
    <div class="h-12 w-12 animate-spin rounded-full border-4 border-border border-t-primary"></div>
</div>
