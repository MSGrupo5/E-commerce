<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
         $products = Product::with('category')->paginate(12);   // trae los productos con su categoría y los pagina de 12 en 12
         return view('products.index', ['products' => $products]);
    }

}
