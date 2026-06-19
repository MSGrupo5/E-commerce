<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index()
    {
        $orderIds = OrderItem::whereHas('product', fn ($q) => $q->where('user_id', auth()->id()))
            ->pluck('order_id')
            ->unique();

        $orders = Order::with(['items' => fn ($q) => $q->whereHas('product', fn ($pq) => $pq->where('user_id', auth()->id())), 'items.product', 'user'])
            ->whereIn('id', $orderIds)
            ->latest()
            ->paginate(15);

        return view('seller.orders.index', compact('orders'));
    }
}
