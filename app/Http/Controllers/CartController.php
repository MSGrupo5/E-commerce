<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = Cart::getOrCreate(auth()->user());

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

        return back()->with('success', 'Producto agregado al carrito con éxito.');
    }
}
