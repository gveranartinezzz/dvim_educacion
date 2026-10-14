<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Support\DosPasos;

class AuthController extends Controller
{
    public function form()
    {
        if (session()->has('id_usuario')) {
            return redirect('/inicio');
        }
        return view('inicio');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo'     => 'required|email',
            'contrasena' => 'required',
        ]);

        $usuario = Usuario::query()->where('correo', $request->correo)->first();

        if (!$usuario) {
            return back()->with('error', 'Correo o contraseña incorrectos.')->withInput();
        }

        // Contraseña: hash bcrypt (normal). Si hubiera una contraseña vieja en texto plano,
        // se acepta una sola vez y se guarda ya cifrada.
        $guardada = (string) $usuario->password;
        $esHash   = str_starts_with($guardada, '$2y$') || str_starts_with($guardada, '$2a$') || str_starts_with($guardada, '$argon');
        $ok       = $esHash ? password_verify($request->contrasena, $guardada)
                            : hash_equals($guardada, (string) $request->contrasena);

        if (!$ok) {
            return back()->with('error', 'Correo o contraseña incorrectos.')->withInput();
        }

        if (!$esHash) {
            Usuario::query()->where('id_usuario', $usuario->id_usuario)
                ->update(['password' => Hash::make($request->contrasena)]);
        }

        if ($usuario->estado !== 'Activo') {
            return back()->with('error', 'Tu usuario está inactivo. Habla con un administrador.')->withInput();
        }

        // Verificación en dos pasos: se manda un código al correo y se espera a que lo escriba
        if (config('divm.dos_pasos') && $usuario->dos_factores_activado) {
            $request->session()->regenerate();
            session(['dos_pasos' => ['id' => $usuario->id_usuario, 'intentos' => 0, 'enviado' => time()]]);

            if (!DosPasos::enviar($usuario)) {
                session()->forget('dos_pasos');
                return back()->with('error', 'No se pudo enviar el código a tu correo. Revisa la configuración MAIL del .env.')->withInput();
            }

            return redirect()->route('verificar');
        }

        $request->session()->regenerate(); // evita fijación de sesión

        session([
            'id_usuario' => $usuario->id_usuario,
            'nombre'     => $usuario->nombre,
            'rol'        => $usuario->rol,
            'correo'     => $usuario->correo,
        ]);

        return redirect('/inicio');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
