<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-muted">
            <a href="{{ route('cart.index') }}" class="hover:text-text transition-colors">Carrito</a>
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <span class="text-text font-medium">Finalizar compra</span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-semibold text-text">Finalizar compra</h1>

        @if($errors->has('stock'))
            <x-ui.alert type="error" :message="$errors->first('stock')" :auto-dismiss="false" />
        @endif

        <div class="grid gap-6 lg:grid-cols-5 lg:items-start">

            {{-- Formulario --}}
            <div class="lg:col-span-3 space-y-4">

                {{-- Dirección de entrega --}}
                <div class="rounded-3xl border border-border bg-surface p-6 sm:p-8">
                    <h2 class="text-base font-semibold text-text mb-5 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary text-xs font-bold">1</span>
                        Dirección de entrega
                    </h2>

                    <div class="space-y-4">
                        <div class="rounded-2xl border border-border bg-background p-4 space-y-1">
                            <p class="text-xs font-semibold text-muted uppercase tracking-wider">Comprando como</p>
                            <p class="text-sm font-medium text-text">{{ $user->name }} {{ $user->apellido }}</p>
                            <p class="text-xs text-muted">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label for="shipping_address" class="block text-small font-medium text-text mb-1.5">
                                Dirección completa
                            </label>
                            <textarea
                                id="shipping_address"
                                name="shipping_address"
                                form="checkout-form"
                                rows="3"
                                required
                                placeholder="Calle, número, piso, ciudad, provincia..."
                                class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted resize-none">{{ old('shipping_address', $user->info_entrega) }}</textarea>
                            @error('shipping_address')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-small font-medium text-text mb-1.5">
                                Teléfono de contacto
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                form="checkout-form"
                                required
                                placeholder="Ej: 11 2345-6789"
                                value="{{ old('phone') }}"
                                class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted">
                            <p class="text-xs text-muted mt-1">Lo usamos para coordinar la entrega del pedido.</p>
                            @error('phone')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-small font-medium text-text mb-1.5">
                                Notas adicionales <span class="text-muted">(opcional)</span>
                            </label>
                            <textarea
                                id="notes"
                                name="notes"
                                form="checkout-form"
                                rows="2"
                                placeholder="Ej: timbre roto, entregar en portería, horario preferido..."
                                class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted resize-none">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Método de pago --}}
                <div class="rounded-3xl border border-border bg-surface p-6 sm:p-8"
                    x-data="{ metodo: '{{ old('payment_method', $efectivoDisponible ? 'efectivo' : 'tarjeta') }}' }">

                    <h2 class="text-base font-semibold text-text mb-5 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary text-xs font-bold">2</span>
                        Método de pago
                    </h2>

                    @error('payment_method')
                        <p class="text-error text-xs mb-3">{{ $message }}</p>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                        {{-- Efectivo --}}
                        <label
                            :class="metodo === 'efectivo'
                                ? 'border-primary bg-primary/5 shadow-[0_0_0_1px] shadow-primary/30'
                                : 'border-border hover:border-primary/40 bg-background'"
                            class="relative flex flex-col items-center gap-3 p-5 rounded-2xl border transition-all {{ $efectivoDisponible ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}">
                            <input type="radio" name="payment_method" value="efectivo" form="checkout-form"
                                x-model="metodo" class="sr-only" @disabled(! $efectivoDisponible)>
                            <div :class="metodo === 'efectivo' ? 'text-primary' : 'text-muted'" class="transition-colors">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p :class="metodo === 'efectivo' ? 'text-primary' : 'text-text'"
                                    class="text-sm font-semibold transition-colors">Efectivo</p>
                                <p class="text-xs text-muted mt-0.5">{{ $efectivoDisponible ? 'Al recibir el pedido' : 'No disponible' }}</p>
                            </div>
                            <div x-show="metodo === 'efectivo'" x-cloak
                                class="absolute top-2.5 right-2.5 w-5 h-5 rounded-full bg-primary flex items-center justify-center">
                                <svg class="w-3 h-3 text-background" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </div>
                        </label>

                        {{-- Tarjeta --}}
                        <label
                            :class="metodo === 'tarjeta'
                                ? 'border-primary bg-primary/5 shadow-[0_0_0_1px] shadow-primary/30'
                                : 'border-border hover:border-primary/40 bg-background'"
                            class="relative flex flex-col items-center gap-3 p-5 rounded-2xl border cursor-pointer transition-all">
                            <input type="radio" name="payment_method" value="tarjeta" form="checkout-form"
                                x-model="metodo" class="sr-only">
                            <div :class="metodo === 'tarjeta' ? 'text-primary' : 'text-muted'" class="transition-colors">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p :class="metodo === 'tarjeta' ? 'text-primary' : 'text-text'"
                                    class="text-sm font-semibold transition-colors">Tarjeta</p>
                                <p class="text-xs text-muted mt-0.5">Débito o crédito</p>
                            </div>
                            <div x-show="metodo === 'tarjeta'" x-cloak
                                class="absolute top-2.5 right-2.5 w-5 h-5 rounded-full bg-primary flex items-center justify-center">
                                <svg class="w-3 h-3 text-background" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </div>
                        </label>

                        {{-- USDT / Crypto --}}
                        <label
                            :class="metodo === 'usdt'
                                ? 'border-primary bg-primary/5 shadow-[0_0_0_1px] shadow-primary/30'
                                : 'border-border hover:border-primary/40 bg-background'"
                            class="relative flex flex-col items-center gap-3 p-5 rounded-2xl border cursor-pointer transition-all">
                            <input type="radio" name="payment_method" value="usdt" form="checkout-form"
                                x-model="metodo" class="sr-only">
                            <div :class="metodo === 'usdt' ? 'text-primary' : 'text-muted'" class="transition-colors">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p :class="metodo === 'usdt' ? 'text-primary' : 'text-text'"
                                    class="text-sm font-semibold transition-colors">USDT / Crypto</p>
                                <p class="text-xs text-muted mt-0.5">Binance · Lemon Cash</p>
                            </div>
                            <div x-show="metodo === 'usdt'" x-cloak
                                class="absolute top-2.5 right-2.5 w-5 h-5 rounded-full bg-primary flex items-center justify-center">
                                <svg class="w-3 h-3 text-background" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </div>
                        </label>

                    </div>

                    @unless($efectivoDisponible)
                        <div class="mt-4 flex items-start gap-3 rounded-2xl border border-warning/25 bg-warning/5 px-4 py-3.5">
                            <svg class="w-4 h-4 text-warning shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                            <p class="text-xs text-warning/90 leading-relaxed">
                                @if(blank($user->provincia) || blank($user->ciudad))
                                    El pago en efectivo requiere coordinar un punto de encuentro. Completá tu provincia y ciudad en tu <a href="{{ route('profile.edit') }}" class="underline font-medium">perfil</a> para habilitarlo.
                                @else
                                    El pago en efectivo no está disponible para este pedido: hay productos de vendedores de otra provincia o ciudad.
                                @endif
                            </p>
                        </div>
                    @endunless

                    {{-- Aviso contextual USDT --}}
                    <div x-show="metodo === 'usdt'" x-cloak x-transition
                        class="mt-4 flex items-start gap-3 rounded-2xl border border-warning/25 bg-warning/5 px-4 py-3.5">
                        <svg class="w-4 h-4 text-warning shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                        <p class="text-xs text-warning/90 leading-relaxed">
                            Al confirmar el pedido vas a ver la dirección de wallet para transferir en USDT. Una vez que pagues, vas a poder pegar el hash de la transacción como comprobante.
                        </p>
                    </div>

                    {{-- Datos de la tarjeta --}}
                    {{-- Nota: estos campos no tienen "name" a propósito — no se envían al servidor ni se
                         almacenan, ya que esta tienda no procesa pagos con tarjeta de forma real todavía. --}}
                    <div x-show="metodo === 'tarjeta'" x-cloak x-transition class="mt-4 space-y-4">
                        <div>
                            <label class="block text-small font-medium text-text mb-1.5">Número de tarjeta</label>
                            <input type="text" inputmode="numeric" autocomplete="off" maxlength="19" placeholder="1234 5678 9012 3456"
                                class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted">
                        </div>

                        <div>
                            <label class="block text-small font-medium text-text mb-1.5">Nombre del titular</label>
                            <input type="text" autocomplete="off" placeholder="Como figura en la tarjeta"
                                class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted">
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-small font-medium text-text mb-1.5">Vencimiento</label>
                                <input type="text" inputmode="numeric" autocomplete="off" maxlength="5" placeholder="MM/AA"
                                    class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted">
                            </div>
                            <div>
                                <label class="block text-small font-medium text-text mb-1.5">CVV</label>
                                <input type="text" inputmode="numeric" autocomplete="off" maxlength="4" placeholder="123"
                                    class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted">
                            </div>
                            <div>
                                <label class="block text-small font-medium text-text mb-1.5">DNI titular</label>
                                <input type="text" inputmode="numeric" autocomplete="off" maxlength="9" placeholder="30123456"
                                    class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted">
                            </div>
                        </div>

                        <div>
                            <label class="block text-small font-medium text-text mb-1.5">Cuotas</label>
                            <select class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors">
                                <option>1 cuota sin interés</option>
                                <option>3 cuotas sin interés</option>
                                <option>6 cuotas con interés</option>
                                <option>12 cuotas con interés</option>
                            </select>
                        </div>

                        <div class="flex items-start gap-3 rounded-2xl border border-border bg-background/60 px-4 py-3.5">
                            <svg class="w-4 h-4 text-muted shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            <p class="text-xs text-muted leading-relaxed">
                                Pago seguro procesado al confirmar el pedido.
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Botón confirmar --}}
                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-2xl bg-primary px-5 py-4 text-sm font-semibold text-background hover:bg-primary/90 transition-colors">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                        Confirmar pedido
                    </button>
                </form>

            </div>

            {{-- Resumen --}}
            <div class="lg:col-span-2 lg:sticky lg:top-24">
                <div class="rounded-3xl border border-border bg-surface p-6 space-y-4">
                    <h2 class="text-base font-semibold text-text">Tu pedido</h2>

                    <ul class="space-y-3">
                        @foreach($items as $item)
                            <li class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-background border border-border shrink-0">
                                    <img src="{{ $item->product->image_url }}"
                                        alt="{{ $item->product->name }}"
                                        class="w-full h-full object-cover"
                                        onerror="this.style.display='none'">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-text truncate">{{ $item->product->name }}</p>
                                    <p class="text-xs text-muted">× {{ $item->quantity }}</p>
                                </div>
                                <p class="text-sm font-semibold text-text shrink-0">
                                    ${{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                </p>
                            </li>
                        @endforeach
                    </ul>

                    <div class="border-t border-border pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-muted">Envío</span>
                            <span class="text-text font-medium">A coordinar con el vendedor</span>
                        </div>
                        <div class="flex items-end justify-between gap-2">
                            <span class="text-sm text-muted">Total</span>
                            <div class="text-right">
                                <span class="font-oxanium text-2xl font-bold text-primary block">
                                    ${{ number_format($total, 0, ',', '.') }}
                                </span>
                                @if(isset($usdToArs) && $usdToArs > 0)
                                    <span class="text-xs text-muted/70 mt-0.5 block">
                                        ≈ USD {{ number_format($total / $usdToArs, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-border bg-background/60 px-4 py-3.5">
                        <svg class="w-4 h-4 text-muted shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <p class="text-xs text-muted leading-relaxed">
                            Tiempo estimado de entrega: <span class="text-text font-medium">3 a 5 días hábiles</span> luego de confirmado el pago.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
