<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = Cart::with('items.product.seller')
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $items = $cart->items;

        return view('checkout.show', compact('items'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'direccion_entrega' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $cart = Cart::with('items.product.seller')
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        // Validar stock de todos los productos antes de iniciar la transacción
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->withErrors([
                    'direccion_entrega' => "El producto '{$item->product->name}' no tiene suficiente stock disponible (disponible: {$item->product->stock}).",
                ]);
            }
        }

        $order = DB::transaction(function () use ($user, $cart, $validated) {
            // Actualizar la dirección de entrega en el perfil del usuario
            if ($user->direccion_entrega !== $validated['direccion_entrega']) {
                $user->update([
                    'direccion_entrega' => $validated['direccion_entrega'],
                ]);
            }

            $total = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);

            // Crear la Orden
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $validated['direccion_entrega'],
            ]);

            // Registrar cada ítem en la orden y descontar stock
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Descontar del stock físico
                $item->product->decrement('stock', $item->quantity);
            }

            // Vaciar el carrito de compras
            $cart->items()->delete();

            return $order;
        });

        // Store success message in session without redirecting to index.
        return redirect()->route('checkout.confirmation', $order)->with('success', '¡Compra confirmada con éxito!');
    }

    public function confirmation(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product.seller']);

        return view('checkout.confirmation', compact('order'));
    }
}
