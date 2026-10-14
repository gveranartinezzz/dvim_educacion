<?php

namespace App\Support;

use App\Mail\CodigoVerificacion;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** Genera, guarda y envía por correo el código de la verificación en dos pasos. */
class DosPasos
{
    /**
     * Crea un código nuevo (columnas codigo_verificacion y fecha_codigo de la tabla usuarios)
     * y lo manda al correo del usuario. Devuelve false si el correo no pudo enviarse.
     */
    public static function enviar(Usuario $usuario): bool
    {
        $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Usuario::query()->where('id_usuario', $usuario->id_usuario)->update([
            'codigo_verificacion' => $codigo,
            'fecha_codigo'        => now(),
        ]);

        try {
            Mail::to($usuario->correo)->send(
                new CodigoVerificacion($usuario->nombre, $codigo, (int) config('divm.codigo_minutos', 10))
            );
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el código de verificación: ' . $e->getMessage());
            return false;
        }

        return true;
    }

    /** Segundos que le quedan de vida al código guardado. */
    public static function segundosRestantes(Usuario $usuario): int
    {
        if (!$usuario->fecha_codigo) {
            return 0;
        }
        $vence = \Illuminate\Support\Carbon::parse($usuario->fecha_codigo)->addMinutes((int) config('divm.codigo_minutos', 10));
        return max(0, $vence->getTimestamp() - now()->getTimestamp());
    }

    /** ab****yz@gmail.com */
    public static function enmascarar(string $correo): string
    {
        [$u, $d] = array_pad(explode('@', $correo, 2), 2, '');
        $mascara = substr($u, 0, 2) . str_repeat('*', max(strlen($u) - 4, 2)) . substr($u, -2);
        return $mascara . '@' . $d;
    }
}
