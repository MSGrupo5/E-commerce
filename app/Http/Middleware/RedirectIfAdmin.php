<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Los administradores no pueden acceder a esta sección.');
        }

        return $next($request);
    }
}
