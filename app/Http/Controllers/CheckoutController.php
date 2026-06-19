<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProcessCheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
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

        $order = Order::create([
            'user_id'         => auth()->id(),
            'total'           => $total,
            'status'          => 'pending',
            'shipping_address' => $request->shipping_address,
            'payment_method'  => $request->payment_method,
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        $cart->items()->delete();

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
}
