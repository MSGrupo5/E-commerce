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
        $totalPedidos = Order::count();
        $totalUsuarios = User::where('role', 'usuario')->count();

        return view('admin.dashboard', compact(
            'totalProductos',
            'totalPedidos',
            'totalUsuarios',
        ));
    }
}
