<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-text">
            {{ __('Confirmación de Compra') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[32px] border border-border bg-surface shadow-[0_24px_64px_rgba(0,0,0,0.25)]">
                
                {{-- Header / Success Message --}}
                <div class="border-b border-border p-8 text-center sm:p-12">
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full border border-green-500/20 bg-green-500/10">
                        <svg class="h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="font-oxanium text-3xl font-bold text-text sm:text-4xl">¡Pedido confirmado!</h1>
                    <p class="mt-4 text-lg text-muted">Gracias por tu compra. Hemos recibido tu pedido y lo estamos procesando.</p>
                    
                    <div class="mt-8 inline-flex items-center rounded-full border border-primary/30 bg-primary/10 px-6 py-2">
                        <span class="text-sm font-semibold uppercase tracking-wider text-muted">Número de pedido:</span>
                        <span class="ml-3 font-oxanium text-xl font-bold text-primary">#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="p-8 sm:p-12">
                    <h3 class="mb-6 text-lg font-semibold uppercase tracking-[0.2em] text-text">Resumen de la orden</h3>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Detalles de entrega y pago --}}
                        <div class="space-y-6 rounded-3xl border border-border bg-background/50 p-6">
                            <div>
                                <h4 class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Dirección de entrega</h4>
                                <p class="mt-2 text-base text-text">{{ $order->shipping_address }}</p>
                            </div>
                            
                            <div class="border-t border-border"></div>
                            
                            <div>
                                <h4 class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Método de pago</h4>
                                <p class="mt-2 text-base text-text">Efectivo</p>
                            </div>
                        </div>

                        {{-- Total y Estado --}}
                        <div class="space-y-6 rounded-3xl border border-border bg-background/50 p-6">
                            <div>
                                <h4 class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Estado del pedido</h4>
                                <div class="mt-2">
                                    <span class="inline-flex rounded-full border border-yellow-500/30 bg-yellow-500/10 px-3 py-1 text-xs font-medium text-yellow-500">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t border-border"></div>

                            <div>
                                <h4 class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Total pagado</h4>
                                <p class="mt-2 font-oxanium text-3xl font-bold text-primary">${{ number_format($order->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Productos Comprados --}}
                    <div class="mt-10">
                        <h4 class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-muted">Productos comprados</h4>
                        <div class="overflow-x-auto overflow-hidden rounded-3xl border border-border bg-background/50">
                            <table class="w-full text-left text-sm text-text min-w-[600px]">
                                <thead class="border-b border-border bg-surface/50">
                                    <tr>
                                        <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Producto</th>
                                        <th class="px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Vendedor</th>
                                        <th class="px-6 py-4 text-center text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Cant.</th>
                                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Precio Unit.</th>
                                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    @foreach($order->items as $item)
                                        <tr class="transition hover:bg-primary/5">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-3">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-10 w-10 rounded-xl object-cover border border-border">
                                                    @else
                                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-surface text-[10px] font-semibold text-muted">IMG</div>
                                                    @endif
                                                    <span class="font-medium text-text">{{ $item->product ? $item->product->name : 'Producto no disponible' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-muted">
                                                {{ $item->product && $item->product->seller ? $item->product->seller->name : 'NexusTech' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-oxanium text-primary">
                                                ${{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-oxanium font-semibold text-text">
                                                ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <a href="{{ route('products.index') }}" class="w-full rounded-2xl bg-primary px-8 py-3 text-center text-sm font-semibold text-background transition hover:bg-primary/90 sm:w-auto">
                            Volver al catálogo
                        </a>
                        <a href="{{ url('/orders') }}" class="w-full rounded-2xl border border-border bg-surface px-8 py-3 text-center text-sm font-medium text-text transition hover:border-primary/50 hover:bg-primary/5 sm:w-auto">
                            Ver mis pedidos
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
