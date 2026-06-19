<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\user;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $products = Product::with(['category', 'seller'])
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->when($request->filled('seller'), fn($q, $id) => $q->where('user_id', $id))
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.productos.index')
            ->with('success', "Producto «{$product->name}» eliminado.");
    }
}
