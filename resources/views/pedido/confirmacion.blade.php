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
            <h1 class="text-h2 font-oxanium font-bold text-text">¡Pedido confirmado!</h1>
            <p class="text-muted text-body leading-relaxed">
                Tu pedido #{{ $order->id }} fue registrado con éxito. Te contactaremos cuando esté en camino.
            </p>
        </div>

        {{-- Detalle del pedido --}}
        <div class="rounded-3xl border border-border bg-surface p-6 text-left space-y-5">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-text">Detalle del pedido</h2>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1 rounded-full bg-warning/10 border border-warning/20 text-warning">
                    <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                    Pendiente
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
                @if($order->phone)
                    <div class="flex justify-between text-sm gap-4">
                        <span class="text-muted shrink-0">Teléfono de contacto</span>
                        <span class="text-text font-medium text-right">{{ $order->phone }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm gap-4">
                    <span class="text-muted shrink-0">Método de pago</span>
                    <span class="text-text font-medium">
                        @php
                            $labels = ['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta (débito / crédito)', 'usdt' => 'USDT / Crypto'];
                        @endphp
                        {{ $labels[$order->payment_method] ?? $order->payment_method }}
                    </span>
                </div>
                @if($order->notes)
                    <div class="flex justify-between text-sm gap-4">
                        <span class="text-muted shrink-0">Notas</span>
                        <span class="text-text font-medium text-right">{{ $order->notes }}</span>
                    </div>
                @endif
                @if($order->payment_method === 'usdt')
                    @php
                        $usdtAmount = isset($usdToArs) && $usdToArs > 0
                            ? number_format($order->total / $usdToArs, 2)
                            : null;
                        $walletAddress = config('services.usdt.wallet_address');
                        $walletNetwork = config('services.usdt.wallet_network');
                    @endphp
                    <div class="space-y-3 rounded-xl border border-warning/25 bg-warning/5 px-4 py-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-warning shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                            <div class="space-y-1.5 text-xs text-warning/90 leading-relaxed">
                                <p>
                                    Transferí
                                    @if($usdtAmount) <span class="font-semibold">{{ $usdtAmount }} USDT</span> @endif
                                    a la siguiente wallet ({{ $walletNetwork }}):
                                </p>
                                @if($walletAddress)
                                    <p class="break-all rounded-lg bg-background/60 px-3 py-2 font-mono text-sm text-text">{{ $walletAddress }}</p>
                                @else
                                    <p>Todavía no configuramos una wallet de destino — te contactaremos para coordinar el pago.</p>
                                @endif
                            </div>
                        </div>

                        @if($order->usdt_tx_hash)
                            <div class="flex items-center gap-2 rounded-lg bg-background/60 px-3 py-2.5 text-xs text-text">
                                <svg class="w-4 h-4 text-success shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                <span>Comprobante recibido: <span class="font-mono">{{ $order->usdt_tx_hash }}</span> · en verificación</span>
                            </div>
                        @else
                            <form action="{{ route('checkout.comprobante', $order) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input type="text" name="usdt_tx_hash" required placeholder="Pegá el hash de tu transacción"
                                        class="flex-1 bg-background border border-border rounded-xl px-3 py-2 text-text text-xs focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted">
                                    <button type="submit"
                                        class="shrink-0 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-background transition hover:bg-primary/90">
                                        Enviar comprobante
                                    </button>
                                </div>
                                @error('usdt_tx_hash')
                                    <p class="text-error text-xs">{{ $message }}</p>
                                @enderror
                            </form>
                        @endif
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
