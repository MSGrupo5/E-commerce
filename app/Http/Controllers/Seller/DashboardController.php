<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Product::where('user_id', auth()->id())->count();

        $totalPedidos = OrderItem::whereHas('product', fn($q) => $q->where('user_id', auth()->id()))
            ->distinct('order_id')
            ->count('order_id');

        return view('seller.dashboard', compact('totalProductos', 'totalPedidos'));
    }
}
