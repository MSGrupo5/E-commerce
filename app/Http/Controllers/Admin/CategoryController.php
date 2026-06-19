<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($validated);

        return to_route('admin.categorias.index')
            ->with('success', "Categoría «{$validated['name']}» creada.");
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
        ]);

        $category->update($validated);

        return to_route('admin.categorias.index')
            ->with('success', "Categoría «{$validated['name']}» actualizada.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return to_route('admin.categorias.index')
                ->with('error', "No se puede eliminar «{$category->name}» porque tiene productos asociados.");
        }

        $category->delete();

        return to_route('admin.categorias.index')
            ->with('success', "Categoría «{$category->name}» eliminada.");
    }
}
