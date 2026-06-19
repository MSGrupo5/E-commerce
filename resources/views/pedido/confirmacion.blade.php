<x-app-layout>
    <div class="max-w-2xl mx-auto text-center space-y-8 py-10">

        {{-- Ícono de éxito --}}
        <div class="flex justify-center">
            <div class="w-20 h-20 rounded-[28px] bg-success/10 border border-success/20 flex items-center justify-center">
                <svg class="w-10 h-10 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
            </div>
        </div>

        <div class="space-y-3">
            <h1 class="text-h2 font-oxanium font-bold text-text">
                {{ $order->isPaid() ? '¡Pago recibido!' : '¡Pedido confirmado!' }}
            </h1>
            <p class="text-muted text-body leading-relaxed">
                @if($order->isPaid())
                    Tu pago para el pedido #{{ $order->id }} fue acreditado correctamente. Prepararemos tu envío a la brevedad.
                @else
                    Tu pedido #{{ $order->id }} fue registrado con éxito. Te contactaremos cuando esté en camino.
                @endif
            </p>
        </div>

        {{-- Detalle del pedido --}}
        <div class="rounded-3xl border border-border bg-surface p-6 text-left space-y-5">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-text">Detalle del pedido</h2>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1 rounded-full
                    {{ $order->isPaid() ? 'bg-success/10 border border-success/20 text-success' : 'bg-warning/10 border border-warning/20 text-warning' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $order->isPaid() ? 'bg-success' : 'bg-warning' }}"></span>
                    {{ $order->isPaid() ? 'Pagado' : 'Pendiente' }}
                </span>
            </div>

            <ul class="space-y-3">
                @foreach($order->items as $item)
                    <li class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-background border border-border shrink-0">
                            @if($item->product?->image)
                                <img src="{{ $item->product->image_url }}"
                                    alt="{{ $item->product?->name }}"
                                    class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-text truncate">{{ $item->product?->name ?? 'Producto eliminado' }}</p>
                            <p class="text-xs text-muted">× {{ $item->quantity }} · ${{ number_format($item->price, 0, ',', '.') }} c/u</p>
                        </div>
                        <p class="text-sm font-semibold text-text shrink-0">
                            ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </p>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-border pt-4 space-y-3">
                <div class="flex justify-between text-sm gap-4">
                    <span class="text-muted shrink-0">Dirección de entrega</span>
                    <span class="text-text font-medium text-right">{{ $order->shipping_address }}</span>
                </div>
                <div class="flex justify-between text-sm gap-4">
                    <span class="text-muted shrink-0">Método de pago</span>
                    <span class="text-text font-medium">
                        @php
                            $labels = ['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta (débito / crédito)', 'usdt' => 'USDT / Crypto', 'mercadopago' => 'Mercado Pago'];
                        @endphp
                        {{ $labels[$order->payment_method] ?? $order->payment_method }}
                    </span>
                </div>
                @if($order->payment_method === 'usdt')
                    <div class="flex items-start gap-3 rounded-xl border border-warning/25 bg-warning/5 px-4 py-3">
                        <svg class="w-4 h-4 text-warning shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                        <p class="text-xs text-warning/90 leading-relaxed">
                            En breve nos contactaremos para enviarte la dirección de wallet para completar tu pago en USDT.
                        </p>
                    </div>
                @endif
                @if($order->payment_method === 'mercadopago')
                    <div class="flex items-start gap-3 rounded-xl border border-accent/25 bg-accent/5 px-4 py-3">
                        <svg class="w-4 h-4 text-accent shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                        </svg>
                        <p class="text-xs text-accent/90 leading-relaxed">
                            @if($order->isPaid())
                                El pago fue acreditado correctamente a través de Mercado Pago.
                            @else
                                Estamos esperando la confirmación del pago de Mercado Pago. Te notificaremos cuando se acredite.
                            @endif
                        </p>
                    </div>
                @endif
                <div class="flex items-end justify-between pt-1">
                    <span class="text-base font-semibold text-text">Total</span>
                    <span class="font-oxanium text-2xl font-bold text-primary">
                        ${{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- CTAs --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center justify-center gap-2 bg-primary text-background font-semibold text-sm px-6 py-3.5 rounded-2xl hover:bg-primary/90 transition-colors">
                Seguir comprando
            </a>
            <a href="{{ route('seller.compras') }}"
                class="inline-flex items-center justify-center gap-2 border border-border bg-surface text-text font-medium text-sm px-6 py-3.5 rounded-2xl hover:bg-background transition-colors">
                Ver historial de compra
            </a>
        </div>

    </div>
</x-app-layout>
