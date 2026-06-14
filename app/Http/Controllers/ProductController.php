<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $categorySlug = $request->input('category');

        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();


        $products = Product::with('category')
            ->when($query, fn($q) => $q->search($query))
            ->when($categorySlug, fn($q) => $q->whereHas('category', fn($cq) => $cq->where('slug', $categorySlug)))
            ->paginate(12);

        return view('products.index', compact('products', 'query', 'categories', 'categorySlug'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        return view('products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (blank($query)) {
            return redirect()->route('products.index');
        }

        $products = Product::with('category')
            ->search($query)
            ->paginate(12);

        return view('products.search', compact('products', 'query'));
    }

}
