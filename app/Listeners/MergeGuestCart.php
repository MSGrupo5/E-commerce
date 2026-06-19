<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Auth\Events\Login;

class MergeGuestCart
{
    public function handle(Login $event): void
    {
        $items = session('cart.items', []);

        if (empty($items)) {
            return;
        }

        $cart = Cart::getOrCreate($event->user);

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if (! $product || ! $product->inStock()) {
                continue;
            }

            $cartItem = $cart->items()->firstOrNew([
                'product_id' => $item['product_id'],
            ]);

            $cartItem->quantity = min(
                $cartItem->quantity + $item['quantity'],
                $product->stock
            );

            $cartItem->save();
        }

        session()->forget('cart');
    }
}
