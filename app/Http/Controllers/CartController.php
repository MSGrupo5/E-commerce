<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product.category')
            ->where('user_id', request()->user()->id)
            ->first();

        return view('cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user = request()->user();

        $cart = Cart::getOrCreate($user);

        $item = $cart->items()->firstOrNew([
            'product_id' => $validated['product_id'],
        ]);

        $newQuantity = $item->quantity + $validated['quantity'];

        $product = Product::findOrFail($validated['product_id']);

        if ($newQuantity > $product->stock) {
            return back()->withErrors([
                'quantity' => 'El stock es insuficiente.',
            ]);
        }

        $item->quantity = $newQuantity;
        $item->save();

        return back()->with('cart_success', 'Producto agregado al carrito con éxito.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->cart->user_id !== request()->user()->id) {
            abort(403);
        }

        if ($validated['quantity'] > $cartItem->product->stock) {
            return back()->withErrors([
                'quantity' => 'El stock es insuficiente.',
            ]);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('cart_success', 'Cantidad actualizada.');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== request()->user()->id) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('cart_success', 'Producto eliminado del carrito.');
    }
}
