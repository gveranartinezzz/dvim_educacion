<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Solo el rol Administrador. */
class SoloAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (session('rol') !== 'Administrador') {
            abort(403, 'Solo administradores.');
        }
        return $next($request);
    }
}
