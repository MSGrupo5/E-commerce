<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('seller.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['user_id'] = auth()->id();

        Product::create($data);

        return redirect()->route('seller.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product)
    {
        $this->authorize('update', $product);

        return redirect()->route('seller.productos.edit', $product);
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $categories = Category::all();

        return view('seller.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('seller.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('seller.productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
