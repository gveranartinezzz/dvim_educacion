<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Anota quién guardó, editó o cambió algo (no guarda contraseñas ni el contenido). */
class RegistrarBitacora
{
    public function handle(Request $request, Closure $next)
    {
        $r = $next($request);

        try {
            $metodo = $request->method();
            $ruta = '/' . ltrim($request->path(), '/');
            $registrable = in_array($metodo, ['POST', 'PUT', 'PATCH', 'DELETE'], true)
                && !str_starts_with($ruta, '/chatbot')
                && !str_starts_with($ruta, '/verificar')
                && $r->getStatusCode() < 400
                && Schema::hasTable('bitacora');

            if ($registrable) {
                DB::table('bitacora')->insert([
                    'id_usuario' => session('id_usuario'),
                    'usuario'    => mb_substr((string) session('nombre', $request->input('correo', 'anónimo')), 0, 150),
                    'accion'     => $metodo,
                    'ruta'       => mb_substr($ruta, 0, 190),
                    'ip'         => $request->ip(),
                    'fecha'      => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // La bitácora nunca debe tumbar el sistema
        }

        return $r;
    }
}
