<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Support\DosPasos;
use Illuminate\Http\Request;

/** Segundo paso del inicio de sesión: código de 6 dígitos enviado al correo. */
class VerificacionController extends Controller
{
    private function pendiente(): ?Usuario
    {
        $id = session('dos_pasos.id');
        return $id ? Usuario::query()->where('id_usuario', $id)->first() : null;
    }

    public function form()
    {
        $usuario = $this->pendiente();
        if (!$usuario) {
            return redirect()->route('login');
        }

        return view('verificar', [
            'correo'    => DosPasos::enmascarar($usuario->correo),
            'segundos'  => DosPasos::segundosRestantes($usuario),
            'modoPrueba' => config('mail.default') === 'log',
        ]);
    }

    public function verificar(Request $request)
    {
        $usuario = $this->pendiente();
        if (!$usuario) {
            return redirect()->route('login');
        }

        $request->validate(['codigo' => 'required|digits:6'], [
            'codigo.required' => 'Escribe el código de 6 dígitos.',
            'codigo.digits'   => 'El código debe tener 6 dígitos.',
        ]);

        // Demasiados intentos: se cancela el inicio de sesión
        $intentos = (int) session('dos_pasos.intentos', 0);
        if ($intentos >= (int) config('divm.codigo_intentos', 5)) {
            session()->forget('dos_pasos');
            return redirect()->route('login')->with('error', 'Demasiados intentos. Inicia sesión de nuevo.');
        }

        if (DosPasos::segundosRestantes($usuario) <= 0) {
            return back()->with('error', 'El código venció. Pide uno nuevo con "Reenviar código".');
        }

        if (!hash_equals((string) $usuario->codigo_verificacion, (string) $request->codigo)) {
            session(['dos_pasos.intentos' => $intentos + 1]);
            $quedan = (int) config('divm.codigo_intentos', 5) - ($intentos + 1);
            return back()->with('error', 'Código incorrecto. Te quedan ' . max($quedan, 0) . ' intentos.');
        }

        // Correcto: el código se gasta y se abre la sesión definitiva
        Usuario::query()->where('id_usuario', $usuario->id_usuario)
            ->update(['codigo_verificacion' => null, 'fecha_codigo' => null]);

        $request->session()->forget('dos_pasos');
        $request->session()->regenerate();
        session([
            'id_usuario' => $usuario->id_usuario,
            'nombre'     => $usuario->nombre,
            'rol'        => $usuario->rol,
            'correo'     => $usuario->correo,
        ]);

        return redirect('/inicio');
    }

    public function reenviar()
    {
        $usuario = $this->pendiente();
        if (!$usuario) {
            return redirect()->route('login');
        }

        $espera = (int) config('divm.codigo_reenvio_seg', 60) - (time() - (int) session('dos_pasos.enviado', 0));
        if ($espera > 0) {
            return back()->with('error', "Espera $espera segundos para pedir otro código.");
        }

        if (!DosPasos::enviar($usuario)) {
            return back()->with('error', 'No se pudo enviar el correo. Revisa la configuración MAIL del archivo .env.');
        }

        session(['dos_pasos.enviado' => time(), 'dos_pasos.intentos' => 0]);
        return back()->with('ok', 'Te enviamos un código nuevo.');
    }
}
