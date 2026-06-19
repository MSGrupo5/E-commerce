<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::when($request->filled('status'), function ($q) use ($request) {
            $q->where('is_active', $request->status === 'active');
        })
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'No se puede desactivar un administrador.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activado' : 'desactivado';

        return back()->with('success', "Usuario «{$user->name}» {$status}.");
    }
}
