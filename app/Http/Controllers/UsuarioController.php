<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    use ConColumnas;

    private function soloAdmin()
    {
        abort_unless(session('rol') === 'Administrador', 403, 'Solo administradores.');
    }

    public function index()
    {
        $this->soloAdmin();
        $usuarios = Usuario::query()->orderBy('nombre')->get();
        return view('usuarios', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $this->soloAdmin();

        $data = $request->validate([
            'nombre'     => 'required|string|max:100',
            'apaterno'   => 'required|string|max:100',
            'amaterno'   => 'required|string|max:100',
            'correo'     => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|min:6',
            'rol'        => 'required|in:Personal,Administrador',
            'estado'     => 'required|in:Activo,Inactivo',
        ]);

        $data['password'] = Hash::make($data['contrasena']);
        unset($data['contrasena']);
        $data['dos_factores_activado'] = 1; // todo usuario nuevo verifica su correo al entrar
        Usuario::query()->create($data);

        return redirect('/usuarios')->with('ok', 'Usuario registrado.');
    }

    public function edit($id)
    {
        $this->soloAdmin();
        $usuario = Usuario::query()->where('id_usuario', $id)->first();
        abort_unless($usuario, 404);
        return view('editar', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $this->soloAdmin();

        $data = $request->validate([
            'nombre'           => 'required|string|max:100',
            'apaterno'         => 'required|string|max:100',
            'correo'           => 'required|email|unique:usuarios,correo,' . $id . ',id_usuario',
            'rol'              => 'required|in:Personal,Administrador',
            'estado'           => 'required|in:Activo,Inactivo',
            'nueva_contrasena' => 'nullable|min:6',
        ]);

        $data['dos_factores_activado'] = $request->boolean('dos_factores_activado') ? 1 : 0;
        $nueva = $data['nueva_contrasena'] ?? null;
        unset($data['nueva_contrasena']);
        if ($nueva) {
            $data['password'] = Hash::make($nueva);
        }

        Usuario::query()->where('id_usuario', $id)->update($data);
        return redirect('/usuarios')->with('ok', 'Usuario actualizado.');
    }

    public function destroy($id)
    {
        $this->soloAdmin();
        if ((int) $id === (int) session('id_usuario')) {
            return back()->withErrors('No puedes eliminar tu propio usuario.');
        }
        return $this->borrar(Usuario::class, 'id_usuario', $id, '/usuarios', 'Usuario eliminado.');
    }
}
