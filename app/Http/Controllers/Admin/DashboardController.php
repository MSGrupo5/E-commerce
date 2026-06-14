<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Product::count();
        $totalPedidos   = Order::count();
        $totalClientes  = User::where('role', 'cliente')->count();

        return view('admin.dashboard', compact(
            'totalProductos',
            'totalPedidos',
            'totalClientes',
        ));
    }
}
