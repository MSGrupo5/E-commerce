<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProcessCheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MercadoPagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cart = Cart::with('items.product')->where('user_id', auth()->id())->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Tu carrito está vacío.');
        }

        $items = $cart->items;
        $total = $items->sum(fn ($item) => $item->product->price * $item->quantity);
        $user = auth()->user();

        return view('pedido.index', compact('items', 'total', 'user'));
    }

    public function store(ProcessCheckoutRequest $request): RedirectResponse
    {
        $cart = Cart::with('items.product')->where('user_id', auth()->id())->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Tu carrito está vacío.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->withErrors([
                    'stock' => "Stock insuficiente para \"{$item->product->name}\".",
                ]);
            }
        }

        $total = $cart->items->sum(fn ($item) => $item->product->price * $item->quantity);

        $items = $cart->items;

        $order = DB::transaction(function () use ($cart, $request, $total) {
            $order = Order::create([
                'user_id'          => auth()->id(),
                'total'            => $total,
                'status'           => 'pending',
                'shipping_address' => $request->shipping_address,
                'payment_method'   => $request->payment_method,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });

        if ($request->payment_method === 'mercadopago') {
            $mp = app(MercadoPagoService::class);

            if ($mp->isAvailable()) {
                $checkoutUrl = $mp->createPreference($order, $items);

                if ($checkoutUrl) {
                    return redirect()->away($checkoutUrl);
                }
            }

            return redirect()->route('checkout.confirmacion', $order)
                ->with('info', 'Mercado Pago no está disponible. Tu pedido quedó registrado como pendiente.');
        }

        return redirect()->route('checkout.confirmacion', $order)
            ->with('success', '¡Pedido realizado con éxito!');
    }

    public function confirmacion(Order $order): View|RedirectResponse
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('pedido.confirmacion', compact('order'));
    }

    public function mpCallback(Request $request, string $status): RedirectResponse
    {
        $mp = app(MercadoPagoService::class);
        $mp->handleCallback($request, $status);

        $externalRef = $request->query('external_reference');
        $order = Order::find((int) $externalRef);

        if ($order && $order->user_id === auth()->id()) {
            return redirect()->route('checkout.confirmacion', $order);
        }

        return redirect()->route('home');
    }

    public function mpWebhook(Request $request): \Illuminate\Http\Response
    {
        $mp = app(MercadoPagoService::class);
        $mp->handleWebhook($request);

        return response('OK', 200);
    }
}
