<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $categorySlug = $request->input('category');

        $categories = Category::withCount('products')->get();

        $products = Product::with('category', 'seller')
            ->where('is_active', true)
            ->when($query, fn ($q) => $q->search($query))
            ->when($categorySlug, fn ($q) => $q->whereHas('category', fn ($cq) => $cq->where('slug', $categorySlug)))
            ->latest()
            ->paginate(12);

        $favoriteIds = auth()->check()
            ? auth()->user()->favorites()->pluck('product_id')
            : collect();

        return view('products.index', compact('products', 'query', 'categories', 'categorySlug', 'favoriteIds'));
    }

    public function show(Product $product)
    {
        if (! $product->is_active) {
            abort(404);
        }

        $isFavorite = auth()->check()
            && auth()->user()->favorites()->where('product_id', $product->id)->exists();

        return view('products.show', compact('product', 'isFavorite'));
    }

    public function suggestions(Request $request)
    {
        $query = trim((string) $request->input('search', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('is_active', true)
            ->search($query)
            ->orderBy('name')
            ->limit(6)
            ->get(['id', 'name', 'price', 'image']);

        return response()->json($products->map(fn (Product $product) => [
            'id' => $product->id,
            'name' => $product->name,
            'price_formatted' => number_format($product->price, 0, ',', '.'),
            'image_url' => $product->image_url,
            'url' => route('products.show', $product),
        ]));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        if (blank($query)) {
            return redirect()->route('products.index');
        }

        $products = Product::with('category', 'seller')
            ->where('is_active', true)
            ->search($query)
            ->paginate(12);

        return view('products.search', compact('products', 'query'));
    }
}
