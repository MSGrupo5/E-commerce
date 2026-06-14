<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $cart = Cart::getOrCreate($user)
            ->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'El carrito está vacío.');
        }

        return view('checkout.show', compact('cart', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => ['required', 'string'],
        ]);

        $user = $request->user();

        $cart = Cart::getOrCreate($user)
            ->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'El carrito está vacío.');
        }

        // Verificar stock ANTES de la transacción
        foreach ($cart->items as $item) {

            if (!$item->product) {
                return back()->with(
                    'error',
                    'Uno de los productos ya no existe.'
                );
            }

            if ($item->product->stock < $item->quantity) {
                return back()->with(
                    'error',
                    "Stock insuficiente para {$item->product->name}"
                );
            }
        }

        DB::transaction(function () use ($cart, $user, $request) {

            $total = 0;

            foreach ($cart->items as $item) {
                $total += $item->product->price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($cart->items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,

                    // cambiar por unit_price si tu tabla usa ese nombre
                    'price' => $item->product->price,
                ]);

                $item->product->decrement(
                    'stock',
                    $item->quantity
                );
            }

            $cart->items()->delete();
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Pedido realizado correctamente.');
    }
}