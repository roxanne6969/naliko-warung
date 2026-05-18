<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KasirMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isKasir())) {
            abort(403, 'Akses ditolak. Hanya kasir atau admin yang diizinkan.');
        }

        return $next($request);
    }
}