<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $hasSellerProducts = OrderItem::where('order_id', $order->id)
            ->whereHas('product', fn ($q) => $q->where('user_id', auth()->id()))
            ->exists();

        if (! $hasSellerProducts) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:paid,cancelled'],
        ]);

        $newStatus = $validated['status'];

        if (! $order->isPending()) {
            return back()->with('error', 'Solo se pueden modificar pedidos pendientes.');
        }

        $order->update(['status' => $newStatus]);

        $mensaje = $newStatus === 'paid'
            ? 'Pedido marcado como pagado.'
            : 'Pedido cancelado.';

        return back()->with('success', $mensaje);
    }
}
