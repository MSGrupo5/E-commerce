<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with(['product.category', 'product.seller'])
            ->get()
            ->pluck('product')
            ->filter(fn ($p) => $p && $p->is_active)
            ->values();

        $favoriteIds = $favorites->pluck('id');

        return view('favoritos.index', compact('favorites', 'favoriteIds'));
    }

    public function toggle(Product $product)
    {
        $user = auth()->user();

        $existing = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $msg = 'Producto eliminado de favoritos.';
        } else {
            Favorite::create(['user_id' => $user->id, 'product_id' => $product->id]);
            $msg = 'Producto agregado a favoritos.';
        }

        return back()->with('success', $msg);
    }
}
