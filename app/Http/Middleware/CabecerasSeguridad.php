<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Cabeceras que protegen el sistema (clics ocultos, tipos de archivo, privacidad). */
class CabecerasSeguridad
{
    public function handle(Request $request, Closure $next)
    {
        $r = $next($request);
        $r->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $r->headers->set('X-Content-Type-Options', 'nosniff');
        $r->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $r->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        if (session()->has('id_usuario')) {
            // Páginas con datos de pacientes: que el navegador no las guarde en caché
            $r->headers->set('Cache-Control', 'no-store, private');
        }
        return $r;
    }
}
