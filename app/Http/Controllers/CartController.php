<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        if (! auth()->check()) {
            $items = $this->guestCartItems();

            return view('cart.index', ['items' => $items, 'cart' => null, 'isGuest' => true]);
        }

        $cart = Cart::with('items.product.category')->where('user_id', auth()->id())->first();
        $items = $cart?->items ?? collect();

        return view('cart.index', compact('cart', 'items') + ['isGuest' => false]);
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        if (! auth()->check()) {
            return $this->storeInSession($product, $quantity);
        }

        $cart = Cart::getOrCreate(auth()->user());
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);

        $newQuantity = $item->quantity + $quantity;

        if ($newQuantity > $product->stock) {
            return back()->withErrors(['quantity' => 'El stock disponible es insuficiente.']);
        }

        $item->quantity = $newQuantity;
        $item->save();

        return back()->with('cart_success', 'Producto agregado al carrito.');
    }

    public function update(UpdateCartRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($cartItem);

        if ($request->quantity > $cartItem->product->stock) {
            return back()->withErrors(['quantity' => 'El stock disponible es insuficiente.']);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('cart_success', 'Cantidad actualizada.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($cartItem);

        $cartItem->delete();

        return back()->with('cart_success', 'Producto eliminado del carrito.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function storeInSession(Product $product, int $quantity): RedirectResponse
    {
        $items = session('cart.items', []);
        $found = false;

        foreach ($items as &$item) {
            if ($item['product_id'] === $product->id) {
                $newQty = $item['quantity'] + $quantity;

                if ($newQty > $product->stock) {
                    return back()->withErrors(['quantity' => 'El stock disponible es insuficiente.']);
                }

                $item['quantity'] = $newQty;
                $found = true;
                break;
            }
        }

        if (! $found) {
            if ($quantity > $product->stock) {
                return back()->withErrors(['quantity' => 'El stock disponible es insuficiente.']);
            }
            $items[] = ['product_id' => $product->id, 'quantity' => $quantity];
        }

        session(['cart.items' => $items]);

        return back()->with('cart_success', 'Producto agregado al carrito.');
    }

    private function guestCartItems(): Collection
    {
        return collect(session('cart.items', []))
            ->map(function (array $data) {
                $product = Product::with('category')->find($data['product_id']);
                if (! $product) {
                    return null;
                }

                return (object) [
                    'id' => null,
                    'quantity' => $data['quantity'],
                    'product' => $product,
                ];
            })
            ->filter()
            ->values();
    }

    private function authorizeCartItem(CartItem $cartItem): void
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
