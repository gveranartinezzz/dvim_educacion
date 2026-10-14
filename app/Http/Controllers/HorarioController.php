<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    use ConColumnas;

    public function index()
    {
        $terapeutas = Usuario::query()->where('estado', 'Activo')->orderBy('nombre')->get();

        $horarios = Horario::query()->from('horarios_atencion as h')
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'h.id_usuario')
            ->select('h.*', DB::raw("CONCAT(u.nombre, ' ', u.apaterno) as terapeuta"))
            ->orderBy('h.hora_inicio')
            ->get();

        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return view('distribucion', compact('terapeutas', 'horarios', 'dias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paciente'    => 'required|string|max:150',
            'id_usuario'  => 'required|integer',
            'dia_semana'  => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'hora_inicio' => 'required',
            'hora_fin'    => 'required|after:hora_inicio',
        ]);

        // Un terapeuta no puede tener dos horarios que se traslapen el mismo día
        $conflicto = Horario::query()
            ->where('id_usuario', $data['id_usuario'])
            ->where('dia_semana', $data['dia_semana'])
            ->where('hora_inicio', '<', $data['hora_fin'])
            ->where('hora_fin', '>', $data['hora_inicio'])
            ->exists();

        if ($conflicto) {
            return back()->withInput()->withErrors('El terapeuta ya tiene un horario que se empalma con ese día y hora.');
        }

        Horario::query()->create($data);

        return redirect('/distribucion')->with('ok', 'Horario guardado.');
    }

    public function destroy($id)
    {
        return $this->borrar(Horario::class, 'id_horario', $id, '/distribucion', 'Horario eliminado.');
    }
}
