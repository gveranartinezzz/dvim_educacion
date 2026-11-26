<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Página de inicio: todos los textos se pueden editar desde el sistema (solo administrador). */
class InicioController extends Controller
{
    public const PREDETERMINADOS = [
        'nombre_centro'  => 'Centro DIVM',
        'subtitulo'      => 'Educación Especial - San Martín Texmelucan, Puebla',
        'cargo'          => 'Dirección General',
        'educadora'      => 'Dalia Irene Vera Martínez',
        'descripcion'    => 'Liderando con compromiso la educación inclusiva y el desarrollo integral de nuestra comunidad estudiantil.',
        'domicilio'      => "C. Ajusco 46, Col. Linda Vista,\n74070, San Martín Texmelucan de Labastida, Pue.",
        'telefono'       => '',
        'horario'        => '',
        'bienvenida'     => 'Bienvenidos al Sistema de Gestión del Centro DIVM',
    ];

    private function tabla(): bool
    {
        try {
            if (!Schema::hasTable('ajustes_centro')) {
                Schema::create('ajustes_centro', function ($t) {
                    $t->string('clave', 60)->primary();
                    $t->text('valor')->nullable();
                });
            }
            return true;
        } catch (\Throwable $e) {
            return false; // sin permisos para crear la tabla: se muestran los textos originales
        }
    }

    private function valores(): array
    {
        $v = self::PREDETERMINADOS;
        if ($this->tabla()) {
            foreach (DB::table('ajustes_centro')->pluck('valor', 'clave') as $k => $val) {
                if (array_key_exists($k, $v) && $val !== null) $v[$k] = $val;
            }
        }
        return $v;
    }

    public function index()
    {
        return view('informacion', ['c' => $this->valores()]);
    }

    public function guardar(Request $r)
    {
        $d = $r->validate([
            'nombre_centro' => 'required|string|min:3|max:80',
            'subtitulo'     => 'required|string|min:3|max:150',
            'cargo'         => 'required|string|max:80',
            'educadora'     => ['required', 'string', 'min:3', 'max:120', 'regex:/^[\pL\pM][\pL\pM\s\.\'’\-]*$/u'],
            'descripcion'   => 'nullable|string|max:400',
            'domicilio'     => 'required|string|min:5|max:250',
            'telefono'      => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'horario'       => 'nullable|string|max:120',
            'bienvenida'    => 'required|string|max:150',
        ], [
            'educadora.regex' => 'El nombre solo puede llevar letras, espacios, puntos y guiones.',
            'telefono.regex'  => 'El teléfono solo puede llevar números, espacios, guiones, paréntesis y +.',
        ], [
            'nombre_centro' => 'nombre del centro', 'subtitulo' => 'subtítulo', 'cargo' => 'cargo',
            'educadora' => 'nombre de la directora o educadora', 'descripcion' => 'descripción',
            'domicilio' => 'domicilio', 'telefono' => 'teléfono', 'horario' => 'horario', 'bienvenida' => 'mensaje de bienvenida',
        ]);

        if (!$this->tabla()) {
            return back()->withInput()->withErrors('No se pudo crear la tabla de ajustes. Corre: php artisan migrate');
        }
        foreach (array_keys(self::PREDETERMINADOS) as $k) {
            DB::table('ajustes_centro')->updateOrInsert(['clave' => $k], ['valor' => $d[$k] ?? '']);
        }
        return redirect('/inicio')->with('ok', 'La información del centro se actualizó.');
    }
}
