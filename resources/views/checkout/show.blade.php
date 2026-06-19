<x-app-layout>
    <div class="space-y-6">
        
        {{-- Encabezado --}}
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-semibold text-text">Checkout</h1>
        </div>

        <div class="grid gap-6 lg:grid-cols-3 lg:items-start">
            
            {{-- Columna Izquierda: Formulario de Checkout --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-3xl border border-border bg-surface p-6 sm:p-8">
                    <h2 class="text-xl font-semibold text-text mb-6">Detalles de Entrega</h2>
                    
                    <form @if(Route::has('checkout.process')) action="{{ route('checkout.process') }}" @else action="#" @endif method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-2">
                            <label for="direccion_entrega" class="block text-sm font-medium text-text">
                                Dirección de entrega
                            </label>
                            <input 
                                id="direccion_entrega" 
                                name="direccion_entrega" 
                                type="text" 
                                class="w-full rounded-2xl border border-border bg-background px-4 py-3 text-sm text-text placeholder-muted transition focus:border-primary/60 focus:outline-none focus:ring-1 focus:ring-primary/30"
                                value="{{ old('direccion_entrega', auth()->user()->direccion_entrega ?? '') }}" 
                                placeholder="Ej: Av. Corrientes 1234"
                                required 
                            />
                            @error('direccion_entrega')
                                <p class="text-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-6 border-t border-border mt-8">
                            <button type="submit" class="w-full rounded-2xl bg-primary px-4 py-4 text-base font-semibold text-background transition hover:bg-primary/90">
                                Confirmar compra
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Columna Derecha: Resumen del carrito --}}
            <div class="lg:sticky lg:top-24">
                <div class="rounded-3xl border border-border bg-surface p-6 space-y-5">
                    <h2 class="text-lg font-semibold text-text">Resumen del pedido</h2>
                    
                    @php
                        // Se asume que $items puede ser inyectado desde el controller
                        $cartItems = $items ?? (auth()->check() && auth()->user()->cart ? auth()->user()->cart->items : collect());
                        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
                    @endphp

                    <ul class="space-y-4">
                        @forelse($cartItems as $item)
                        <li class="flex items-start justify-between gap-3 text-sm">
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded-xl object-cover bg-background border border-border" onerror="this.style.display='none'">
                                <div>
                                    <span class="text-text font-medium line-clamp-1">{{ $item->product->name }}</span>
                                    <div class="flex flex-col">
                                        <span class="text-muted text-xs">Cant: {{ $item->quantity }}</span>
                                        @if($item->product->seller)
                                            <span class="text-accent text-[11px] leading-tight mt-0.5">Vendido por: {{ $item->product->seller->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="shrink-0 text-text font-semibold">${{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</span>
                        </li>
                        @empty
                        <li class="text-muted text-sm">No hay productos en el carrito.</li>
                        @endforelse
                    </ul>

                    <div class="border-t border-border pt-5 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-muted">Subtotal</span>
                            <span class="text-text font-medium">${{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-end justify-between gap-2">
                            <span class="text-base font-semibold text-text">Total</span>
                            <span class="font-oxanium text-3xl sm:text-4xl font-bold text-primary leading-none">
                                ${{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Nota de método de pago --}}
                    <div class="rounded-2xl border border-accent/25 bg-accent/5 p-4 mt-2">
                        <p class="text-sm font-medium text-accent text-center">
                            Método de pago: Efectivo
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
