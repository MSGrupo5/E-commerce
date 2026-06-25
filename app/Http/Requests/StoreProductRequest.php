<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\SinContenidoOfensivo;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Product::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', new SinContenidoOfensivo],
            'description' => ['nullable', 'string', 'max:1000', new SinContenidoOfensivo],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'price' => 'precio',
            'stock' => 'stock',
            'category_id' => 'categoría',
            'image' => 'imagen',
        ];
    }
}
