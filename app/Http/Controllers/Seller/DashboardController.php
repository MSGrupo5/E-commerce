<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    public function index()
    {
        $productsCount = auth()->user()->products()->count();
        $salesCount = OrderItem::whereHas('product', fn ($q) => $q->where('user_id', auth()->id()))->count();

        return view('seller.dashboard', compact('productsCount', 'salesCount'));
    }
}
