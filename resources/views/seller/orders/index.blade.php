@extends('seller.layout')

@section('seller-content')
    <div class="mb-6">
        <h1 class="text-h3 font-oxanium font-bold text-text">Pedidos Recibidos</h1>
        <p class="text-muted text-small">Pedidos que contienen productos de tu catálogo</p>
    </div>

    @if($orders->isEmpty())
        <div class="rounded-3xl border border-border bg-surface p-12 text-center">
            <svg class="w-16 h-16 text-muted/40 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <circle cx="8" cy="21" r="1" />
                <circle cx="19" cy="21" r="1" />
                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
            </svg>
            <p class="text-h5 font-oxanium font-semibold text-text mb-1">No hay pedidos todavía</p>
            <p class="text-muted text-small">Todavía no recibiste pedidos — compartí tus productos para empezar a vender.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="rounded-2xl border border-border bg-surface p-5">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-small font-medium text-text">Pedido #{{ $order->id }}</p>
                            <p class="text-xs text-muted">Realizado el {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-muted">Cliente: {{ $order->user?->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            @if($order->isPending())
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-warning/10 text-warning text-[11px] font-semibold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                                    Pendiente
                                </span>
                            @elseif($order->isPaid())
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-success/10 text-success text-[11px] font-semibold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                    Pagado
                                </span>
                            @elseif($order->isCancelled())
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-error/10 text-error text-[11px] font-semibold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                    Cancelado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-border">
                                    <th class="pb-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Producto</th>
                                    <th class="pb-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Cantidad</th>
                                    <th class="pb-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Precio</th>
                                    <th class="pb-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-b border-border/50 last:border-b-0">
                                        <td class="py-2">
                                            <div class="flex items-center gap-3">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                                        class="w-9 h-9 rounded-lg object-cover border border-border shrink-0">
                                                @else
                                                    <div class="w-9 h-9 rounded-lg bg-background border border-border flex items-center justify-center text-muted shrink-0">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                            <rect x="4" y="4" width="16" height="16" rx="2" />
                                                            <rect x="9" y="9" width="6" height="6" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="text-small text-text">{{ $item->product?->name ?? 'Producto eliminado' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-2 text-small text-muted">{{ $item->quantity }}</td>
                                        <td class="py-2 text-small text-muted">${{ number_format($item->price, 2) }}</td>
                                        <td class="py-2 text-small text-text font-medium">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @php
                        $sellerTotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
                    @endphp
                    <div class="mt-3 text-right">
                        <p class="text-small text-muted">
                            Total (tus productos): <span class="text-text font-semibold">${{ number_format($sellerTotal, 2) }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
