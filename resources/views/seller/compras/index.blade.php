<x-seller-layout>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-h3 font-oxanium font-bold text-text">Mis Compras</h1>
            <p class="text-muted text-small">Historial de todos los pedidos que realizaste como comprador.</p>
        </div>
        @if($orders->isNotEmpty())
            <span class="rounded-full border border-border bg-background px-3 py-1.5 text-xs font-medium text-muted">
                {{ $orders->total() }} {{ $orders->total() === 1 ? 'pedido' : 'pedidos' }}
            </span>
        @endif
    </div>

    @if($orders->isEmpty())

        <x-ui.empty-state
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/>'
            title="Todavía no realizaste ninguna compra"
            description="Explorá el catálogo y encontrá productos que te interesen."
        >
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-background font-semibold text-small hover:bg-primary/90 transition-colors">
                Ver catálogo
            </a>
        </x-ui.empty-state>

    @else

        <div class="space-y-3">
            @foreach($orders as $order)
            @php
                $statusConfig = match($order->status) {
                    'paid'       => ['label' => 'Pagado',    'bg' => 'bg-success/10',  'text' => 'text-success',  'dot' => 'bg-success'],
                    'shipped'    => ['label' => 'Enviado',   'bg' => 'bg-primary/10',  'text' => 'text-primary',  'dot' => 'bg-primary'],
                    'delivered'  => ['label' => 'Entregado', 'bg' => 'bg-success/10',  'text' => 'text-success',  'dot' => 'bg-success'],
                    'cancelled'  => ['label' => 'Cancelado', 'bg' => 'bg-error/10',    'text' => 'text-error',    'dot' => 'bg-error'],
                    default      => ['label' => 'Pendiente', 'bg' => 'bg-warning/10',  'text' => 'text-warning',  'dot' => 'bg-warning'],
                };
                $paymentLabels = ['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'usdt' => 'USDT / Crypto'];
            @endphp

            <div x-data="{ open: false }" class="rounded-2xl border border-border bg-background overflow-hidden">

                {{-- Cabecera del pedido --}}
                <button type="button" @click="open = !open"
                    class="w-full flex flex-wrap items-center gap-x-4 gap-y-2 px-5 py-4 text-left hover:bg-surface/50 transition-colors">

                    {{-- Número y fecha --}}
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 border border-primary/20">
                            <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-text">Pedido #{{ $order->id }}</p>
                            <p class="text-xs text-muted">{{ $order->created_at->format('d/m/Y · H:i') }}</p>
                        </div>
                    </div>

                    {{-- Badges de estado y pago --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                            {{ $statusConfig['label'] }}
                        </span>
                        <span class="inline-flex items-center rounded-full border border-border bg-surface px-2.5 py-1 text-xs text-muted">
                            {{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}
                        </span>
                    </div>

                    {{-- Total --}}
                    <div class="text-right shrink-0 ml-auto">
                        <p class="text-base font-bold font-oxanium text-primary">
                            ${{ number_format($order->total, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-muted">{{ $order->items->count() }} {{ $order->items->count() === 1 ? 'producto' : 'productos' }}</p>
                    </div>

                    {{-- Flecha --}}
                    <svg class="h-4 w-4 text-muted shrink-0 transition-transform duration-200"
                        :class="open ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                    </svg>

                </button>

                {{-- Detalle expandible --}}
                <div x-show="open" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1">

                    <div class="border-t border-border px-5 py-4 space-y-4">

                        {{-- Items --}}
                        <ul class="space-y-3">
                            @foreach($order->items as $item)
                            <li class="flex items-center gap-3">
                                {{-- Imagen --}}
                                <div class="w-10 h-10 rounded-xl overflow-hidden border border-border bg-surface shrink-0">
                                    @if($item->product?->image_url)
                                        <img src="{{ $item->product->image_url }}"
                                            alt="{{ $item->product->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-muted">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Nombre y precio --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-text truncate">
                                        {{ $item->product?->name ?? 'Producto eliminado' }}
                                    </p>
                                    <p class="text-xs text-muted">
                                        {{ $item->quantity }} × ${{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                {{-- Subtotal --}}
                                <p class="text-sm font-semibold text-text shrink-0">
                                    ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </li>
                            @endforeach
                        </ul>

                        {{-- Dirección y totales --}}
                        <div class="border-t border-border/60 pt-3 space-y-2">
                            <div class="flex items-start justify-between gap-4 text-xs">
                                <span class="text-muted shrink-0">Dirección de entrega</span>
                                <span class="text-text font-medium text-right">{{ $order->shipping_address }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4 pt-1">
                                <span class="text-sm font-semibold text-text">Total</span>
                                <span class="font-oxanium text-xl font-bold text-primary">
                                    ${{ number_format($order->total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>

    @endif

</x-seller-layout>
