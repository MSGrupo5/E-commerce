<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\View\View;

class BalanceController extends Controller
{
    public function index(): View
    {
        $commissionRate = config('marketplace.commission_rate');

        $items = OrderItem::whereHas('product', fn ($q) => $q->where('user_id', auth()->id()))
            ->whereHas('order', fn ($q) => $q->where('status', 'paid'))
            ->with(['product', 'order'])
            ->latest('id')
            ->get();

        $bruto = $items->sum(fn ($i) => $i->quantity * $i->price);
        $comision = $bruto * ($commissionRate / 100);
        $neto = $bruto - $comision;

        return view('seller.balance', compact(
            'items',
            'bruto',
            'comision',
            'neto',
            'commissionRate'
        ));
    }
}
