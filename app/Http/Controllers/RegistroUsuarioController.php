<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Crear cuenta desde la pantalla de inicio (público).
 *
 * Por seguridad del sistema (hay datos de pacientes): la cuenta SIEMPRE se crea con rol "Personal"
 * y, por defecto, queda "Inactivo" hasta que un administrador la active en Usuarios → Editar.
 * Para que las cuentas nuevas entren sin aprobación: REGISTRO_APROBACION=false en el .env.
 */
class RegistroUsuarioController extends Controller
{
    public function form()
    {
        if (session()->has('id_usuario')) {
            return redirect('/inicio');
        }
        return view('crear_cuenta');
    }

    public function guardar(Request $request)
    {
        if (session()->has('id_usuario')) {
            return redirect('/inicio');
        }

        // Campo trampa: las personas no lo ven ni lo llenan. Si trae algo, es un robot:
        // se responde como si todo saliera bien, pero no se crea nada.
        if (filled($request->input('sitio_web'))) {
            return redirect()->route('crear-cuenta')->with('creada', $this->mensaje());
        }

        if (is_string($request->input('correo'))) {
            $request->merge(['correo' => mb_strtolower(trim($request->input('correo')))]);
        }

        $data = $request->validate([
            'nombre'     => 'required|string|max:100',
            'apaterno'   => 'required|string|max:100',
            'amaterno'   => 'required|string|max:100',
            'correo'     => 'required|email|max:150|unique:usuarios,correo',
            'contrasena' => 'required|string|min:8|max:72|confirmed',
        ], [
            'required'            => 'Falta llenar: :attribute.',
            'correo.email'        => 'Escribe un correo válido.',
            'correo.unique'       => 'Ese correo ya tiene una cuenta. Inicia sesión o pide ayuda a un administrador.',
            'contrasena.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
            'max'                 => 'El campo :attribute es demasiado largo.',
        ], [
            'nombre'     => 'nombre(s)',
            'apaterno'   => 'apellido paterno',
            'amaterno'   => 'apellido materno',
            'correo'     => 'correo',
            'contrasena' => 'contraseña',
        ]);

        Usuario::query()->create([
            'nombre'                => trim($data['nombre']),
            'apaterno'              => trim($data['apaterno']),
            'amaterno'              => trim($data['amaterno']),
            'correo'                => $data['correo'],
            'password'              => Hash::make($data['contrasena']),
            'rol'                   => 'Personal',   // nunca se toma del formulario
            'estado'                => config('divm.registro_aprobacion', true) ? 'Inactivo' : 'Activo',
            'dos_factores_activado' => 1,            // igual que los usuarios que crea el administrador
        ]);

        return redirect()->route('crear-cuenta')->with('creada', $this->mensaje());
    }

    private function mensaje(): string
    {
        return config('divm.registro_aprobacion', true)
            ? 'Tu cuenta se creó. Un administrador debe activarla antes de que puedas iniciar sesión.'
            : 'Tu cuenta se creó. Ya puedes iniciar sesión.';
    }
}
