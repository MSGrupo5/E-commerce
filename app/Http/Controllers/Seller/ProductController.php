<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('seller.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('seller.products.form', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['user_id'] = auth()->id();

        Product::create($data);

        return redirect()->route('seller.productos.index')
            ->with('success', '¡Producto subido con éxito! Ya está visible en el catálogo.');
    }

    public function show(Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        return redirect()->route('seller.productos.edit', $product);
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        $categories = Category::all();

        return view('seller.products.form', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('seller.productos.index')
            ->with('success', '¡Cambios guardados con éxito!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('seller.productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    public function toggleActivo(Product $product): RedirectResponse
    {
        $this->authorize('toggleActivo', $product);

        $product->update(['is_active' => ! $product->is_active]);

        $mensaje = $product->is_active ? 'Producto activado.' : 'Producto desactivado.';

        return back()->with('success', $mensaje);
    }
}
