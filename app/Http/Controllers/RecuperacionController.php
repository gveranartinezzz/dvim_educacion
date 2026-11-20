<?php

namespace App\Http\Controllers;

use App\Mail\CodigoRecuperacion;
use App\Models\Usuario;
use App\Support\DosPasos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Recuperación de contraseña con un código de 6 dígitos enviado al correo.
 *
 * - El código NO se guarda en la base de datos: se guarda cifrado (HMAC) en la caché
 *   con vencimiento, así que no hace falta cambiar ninguna tabla.
 * - Siempre se responde lo mismo exista o no el correo (nadie puede averiguar quién tiene cuenta).
 * - Solo se manda código a usuarios Activos.
 */
class RecuperacionController extends Controller
{
    private function clave(int $idUsuario): string
    {
        return 'recuperar-clave:' . $idUsuario;
    }

    /** Huella del código (HMAC con la APP_KEY): en la caché nunca queda el código en claro. */
    private function huella(string $codigo): string
    {
        return hash_hmac('sha256', $codigo, (string) config('app.key'));
    }

    private function minutos(): int
    {
        return (int) config('divm.codigo_minutos', 10);
    }

    /** Crea el código, lo guarda en la caché y lo manda por correo. */
    private function generarYEnviar(Usuario $usuario): void
    {
        $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $vence  = time() + $this->minutos() * 60;

        Cache::put($this->clave($usuario->id_usuario), [
            'huella'   => $this->huella($codigo),
            'intentos' => 0,
            'creado'   => time(),
            'vence'    => $vence,
        ], now()->addSeconds($vence - time()));

        try {
            Mail::to($usuario->correo)->send(new CodigoRecuperacion($usuario->nombre, $codigo, $this->minutos()));
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el código de recuperación: ' . $e->getMessage());
        }
    }

    /** Manda el código si el usuario existe, está activo y ya pasó la espera entre reenvíos. */
    private function procesarSolicitud(string $correo): void
    {
        $usuario = Usuario::query()->where('correo', $correo)->first();
        if (!$usuario || $usuario->estado !== 'Activo') {
            return;
        }

        $previo = Cache::get($this->clave($usuario->id_usuario));
        $espera = (int) config('divm.codigo_reenvio_seg', 60);
        if ($previo && (time() - (int) ($previo['creado'] ?? 0)) < $espera) {
            return; // ya se mandó uno hace poco
        }

        $this->generarYEnviar($usuario);
    }

    // ---------- Paso 1: pedir el código ----------

    public function form()
    {
        if (session()->has('id_usuario')) {
            return redirect('/inicio');
        }

        return view('recuperar', ['modoPrueba' => config('mail.default') === 'log']);
    }

    public function enviar(Request $request)
    {
        $request->validate(['correo' => 'required|email'], [
            'correo.required' => 'Escribe tu correo.',
            'correo.email'    => 'Escribe un correo válido.',
        ]);

        $this->procesarSolicitud($request->correo);

        // Misma respuesta siempre, exista o no la cuenta
        session(['recuperar' => ['correo' => $request->correo]]);

        return redirect()->route('recuperar.codigo')
            ->with('ok', 'Si el correo está registrado, te enviamos un código de 6 dígitos.');
    }

    // ---------- Paso 2: código + contraseña nueva ----------

    public function formCodigo()
    {
        $correo = session('recuperar.correo');
        if (!$correo) {
            return redirect()->route('recuperar');
        }

        return view('recuperar_codigo', [
            'correo'     => DosPasos::enmascarar($correo),
            'minutos'    => $this->minutos(),
            'modoPrueba' => config('mail.default') === 'log',
        ]);
    }

    public function reenviar()
    {
        $correo = session('recuperar.correo');
        if (!$correo) {
            return redirect()->route('recuperar');
        }

        $this->procesarSolicitud($correo);

        return back()->with('ok', 'Si el correo está registrado, te enviamos un código nuevo. Espera un minuto antes de pedir otro.');
    }

    public function restablecer(Request $request)
    {
        $correo = session('recuperar.correo');
        if (!$correo) {
            return redirect()->route('recuperar');
        }

        $request->validate([
            'codigo'   => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ], [
            'codigo.required'    => 'Escribe el código de 6 dígitos.',
            'codigo.digits'      => 'El código debe tener 6 dígitos.',
            'password.required'  => 'Escribe tu nueva contraseña.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = Usuario::query()->where('correo', $correo)->first();
        $registro = ($usuario && $usuario->estado === 'Activo')
            ? Cache::get($this->clave($usuario->id_usuario))
            : null;

        if (!$registro || time() >= (int) ($registro['vence'] ?? 0)) {
            return back()->with('error', 'El código venció o no es válido. Pide uno nuevo con "Reenviar código".');
        }

        // Demasiados intentos: el código se invalida
        $maximo = (int) config('divm.codigo_intentos', 5);
        if ((int) $registro['intentos'] >= $maximo) {
            Cache::forget($this->clave($usuario->id_usuario));
            session()->forget('recuperar');
            return redirect()->route('recuperar')->with('error', 'Demasiados intentos. Solicita un código nuevo.');
        }

        if (!hash_equals((string) $registro['huella'], $this->huella((string) $request->codigo))) {
            $registro['intentos'] = (int) $registro['intentos'] + 1;
            Cache::put(
                $this->clave($usuario->id_usuario),
                $registro,
                now()->addSeconds(max(1, (int) $registro['vence'] - time()))
            );
            $quedan = max($maximo - $registro['intentos'], 0);
            return back()->with('error', 'Código incorrecto. Te quedan ' . $quedan . ' intentos.');
        }

        // Todo bien: se guarda la contraseña nueva (bcrypt) y el código ya no sirve
        Usuario::query()->where('id_usuario', $usuario->id_usuario)
            ->update(['password' => Hash::make($request->password)]);

        Cache::forget($this->clave($usuario->id_usuario));
        $request->session()->forget(['recuperar', 'dos_pasos']);
        $request->session()->regenerate();

        return redirect()->route('login')->with('ok', 'Contraseña actualizada. Ya puedes iniciar sesión.');
    }
}
