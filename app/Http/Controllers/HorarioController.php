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

    private const DIAS = 'Lunes,Martes,Miércoles,Jueves,Viernes,Sábado';

    /** Reglas comunes de crear y editar un horario. Devuelve los datos ya revisados. */
    private function revisar(Request $request, ?int $id = null, ?string $pacienteActual = null): array
    {
        $data = $request->validate([
            'paciente'    => $this->reglaPaciente($pacienteActual),
            'id_usuario'  => 'required|integer|exists:usuarios,id_usuario',
            'dia_semana'  => 'required|in:' . self::DIAS,
            'hora_inicio' => 'required|date_format:H:i|after_or_equal:07:00|before:20:00',
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio|before_or_equal:20:00',
        ], [
            'dia_semana.in'              => 'Elige un día de lunes a sábado.',
            'hora_inicio.date_format'    => 'La hora de inicio debe tener formato de 24 horas, por ejemplo 16:00.',
            'hora_fin.date_format'       => 'La hora de fin debe tener formato de 24 horas, por ejemplo 17:00.',
            'hora_inicio.after_or_equal' => 'El centro atiende desde las 07:00.',
            'hora_inicio.before'         => 'La hora de inicio debe ser antes de las 20:00.',
            'hora_fin.after'             => 'La hora de fin debe ser posterior a la hora de inicio.',
            'hora_fin.before_or_equal'   => 'El centro atiende hasta las 20:00.',
        ]);

        $traslape = fn ($campo, $valor) => Horario::query()
            ->where($campo, $valor)
            ->where('dia_semana', $data['dia_semana'])
            ->where('hora_inicio', '<', $data['hora_fin'])
            ->where('hora_fin', '>', $data['hora_inicio'])
            ->when($id, fn ($q) => $q->where('id_horario', '!=', $id))
            ->exists();

        // Un terapeuta no puede tener dos horarios que se traslapen el mismo día
        if ($traslape('id_usuario', $data['id_usuario'])) {
            abort(back()->withInput()->withErrors('El terapeuta ya tiene un horario que se empalma con ese día y hora.'));
        }
        // Un paciente tampoco puede estar en dos lugares a la vez
        if ($traslape('paciente', $data['paciente'])) {
            abort(back()->withInput()->withErrors('Ese paciente ya tiene un horario que se empalma con ese día y hora.'));
        }
        return $data;
    }

    public function store(Request $request)
    {
        Horario::query()->create($this->revisar($request));

        return redirect('/distribucion')->with('ok', 'Horario guardado.');
    }

    public function update(Request $request, $id)
    {
        $h = Horario::query()->findOrFail($id);
        $h->update($this->revisar($request, (int) $id, $h->paciente));

        return redirect('/distribucion')->with('ok', 'Horario actualizado.');
    }

    public function destroy($id)
    {
        return $this->borrar(Horario::class, 'id_horario', $id, '/distribucion', 'Horario eliminado.');
    }
}
