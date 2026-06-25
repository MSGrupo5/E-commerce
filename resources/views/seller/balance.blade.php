<x-seller-layout>
    <div class="mb-6">
        <h1 class="text-h3 font-oxanium font-bold text-text">Saldo Disponible</h1>
        <p class="text-muted text-small">Resumen de tus ventas pagadas y comisiones de la plataforma</p>
    </div>

    @if(session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="rounded-3xl border border-border bg-surface p-6 flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Ventas Brutas</p>
                <p class="text-h3 font-bold text-text font-oxanium">${{ number_format($bruto, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-surface p-6 flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-warning/10 border border-warning/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Comisión ({{ $commissionRate }}%)</p>
                <p class="text-h3 font-bold text-text font-oxanium">${{ number_format($comision, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-success/30 bg-success/5 p-6 flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-success/10 border border-success/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 0 4.5 6h.75M3.75 4.5h12.5A.75.75 0 0 1 17 5.25v.75m-13.25 0A.75.75 0 0 0 4.5 7.5h.75M3.75 7.5H15m0 0A.75.75 0 0 1 15.75 8.25v9.75m-12-9.75v9.75c0 .621.504 1.125 1.125 1.125h9.75" />
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Saldo Disponible</p>
                <p class="text-h3 font-bold text-text font-oxanium">${{ number_format($neto, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    @if($items->isEmpty())
        <x-ui.empty-state
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 0 4.5 6h.75M3.75 4.5h12.5A.75.75 0 0 1 17 5.25v.75m-13.25 0A.75.75 0 0 0 4.5 7.5h.75M3.75 7.5H15m0 0A.75.75 0 0 1 15.75 8.25v9.75m-12-9.75v9.75c0 .621.504 1.125 1.125 1.125h9.75" />'
            title="No hay ventas pagadas todavía"
            description="Cuando un cliente pague uno de tus pedidos, el saldo aparecerá aquí."
        />
    @else
        <div class="rounded-2xl border border-border bg-background overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Producto</th>
                            <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Fecha</th>
                            <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Cantidad</th>
                            <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Precio Unit.</th>
                            <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="border-b border-border/50 last:border-b-0">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                                class="w-9 h-9 rounded-lg object-cover border border-border shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-lg bg-surface border border-border flex items-center justify-center text-muted shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <rect x="4" y="4" width="16" height="16" rx="2" />
                                                    <rect x="9" y="9" width="6" height="6" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="text-small text-text">{{ $item->product?->name ?? 'Producto eliminado' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-small text-muted">{{ $item->order?->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-5 py-3 text-small text-muted">{{ $item->quantity }}</td>
                                <td class="px-5 py-3 text-small text-muted">${{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-small text-text font-medium">${{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-seller-layout>
