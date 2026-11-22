<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Solo personas con sesión iniciada (si no, al inicio de sesión). */
class SoloSesion
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('id_usuario')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
