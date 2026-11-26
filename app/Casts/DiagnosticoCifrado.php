<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Guarda el diagnóstico CIFRADO en la base de datos (con la APP_KEY) y lo muestra normal dentro del sistema.
 * Si encuentra un diagnóstico antiguo sin cifrar, lo muestra tal cual y se cifra la próxima vez que se guarde
 * (o de golpe con: php artisan diagnosticos:cifrar).
 */
class DiagnosticoCifrado implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') return $value;
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return $value; // texto antiguo sin cifrar
        }
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') return $value;
        try {
            Crypt::decryptString($value);
            return $value; // ya venía cifrado: no se cifra dos veces
        } catch (DecryptException $e) {
            return Crypt::encryptString((string) $value);
        }
    }
}
