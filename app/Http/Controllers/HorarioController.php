<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HorarioController extends Controller
{
    use ConColumnas;

    /** Agrega las columnas de fecha si todavía no existen (así funciona aunque no corras migrate). */
    private function asegurarColumnas(): void
    {
        try {
            Schema::table('horarios_atencion', function ($t) {
                if (!Schema::hasColumn('horarios_atencion', 'fecha'))  $t->date('fecha')->nullable();
                if (!Schema::hasColumn('horarios_atencion', 'repite')) $t->boolean('repite')->default(false);
            });
        } catch (\Throwable $e) {
            // sin permiso para alterar la tabla: se usa como antes (sin fecha)
        }
    }

    public function index()
    {
        $this->asegurarColumnas();
        $terapeutas = Usuario::query()->where('estado', 'Activo')->orderBy('nombre')->get();

        $horarios = Horario::query()->from('horarios_atencion as h')
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'h.id_usuario')
            ->select('h.*', DB::raw("CONCAT(u.nombre, ' ', u.apaterno) as terapeuta"))
            ->orderByRaw('h.fecha IS NULL')->orderBy('h.fecha')->orderBy('h.hora_inicio')
            ->get();

        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return view('distribucion', compact('terapeutas', 'horarios', 'dias'));
    }

    private const DIAS = 'Lunes,Martes,Miércoles,Jueves,Viernes,Sábado';

    private const NOMBRE_DIA = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'];

    /** Reglas comunes de crear y editar un horario. Devuelve los datos ya revisados. */
    private function revisar(Request $request, ?int $id = null, ?string $pacienteActual = null): array
    {
        $this->asegurarColumnas();
        $data = $request->validate([
            'paciente'    => $this->reglaPaciente($pacienteActual),
            'id_usuario'  => 'required|integer|exists:usuarios,id_usuario',
            'fecha'       => 'required|date_format:Y-m-d|after:1999-12-31|before:2100-01-01',
            'repite'      => 'nullable|boolean',
            'hora_inicio' => 'required|date_format:H:i|after_or_equal:07:00|before:20:00',
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio|before_or_equal:20:00',
        ], [
            'fecha.required'             => 'Elige la fecha del horario.',
            'fecha.date_format'          => 'La fecha no es válida.',
            'hora_inicio.date_format'    => 'La hora de inicio debe tener formato de 24 horas, por ejemplo 16:00.',
            'hora_fin.date_format'       => 'La hora de fin debe tener formato de 24 horas, por ejemplo 17:00.',
            'hora_inicio.after_or_equal' => 'El centro atiende desde las 07:00.',
            'hora_inicio.before'         => 'La hora de inicio debe ser antes de las 20:00.',
            'hora_fin.after'             => 'La hora de fin debe ser posterior a la hora de inicio.',
            'hora_fin.before_or_equal'   => 'El centro atiende hasta las 20:00.',
        ]);

        $f = Carbon::createFromFormat('Y-m-d', $data['fecha']);
        if ($f->dayOfWeekIso === 7) {
            abort(back()->withInput()->withErrors('El centro no atiende los domingos; elige una fecha de lunes a sábado.'));
        }
        if (!$id && $data['fecha'] < now()->toDateString()) {
            abort(back()->withInput()->withErrors('No se pueden asignar horarios en fechas que ya pasaron.'));
        }
        $data['dia_semana'] = self::NOMBRE_DIA[$f->dayOfWeekIso];
        $data['repite']     = !empty($data['repite']);

        // ¿Se empalma con otro horario? Cuenta el mismo día de la semana, las mismas horas y que las fechas coincidan.
        $traslape = function ($campo, $valor) use ($data, $id) {
            $nuevoRepite = $data['repite'];
            return Horario::query()
                ->where($campo, $valor)
                ->where('dia_semana', $data['dia_semana'])
                ->where('hora_inicio', '<', $data['hora_fin'])
                ->where('hora_fin', '>', $data['hora_inicio'])
                ->when($id, fn ($q) => $q->where('id_horario', '!=', $id))
                ->where(function ($q) use ($data, $nuevoRepite) {
                    if ($nuevoRepite) {
                        // el nuevo se repite cada semana: choca con otros que se repiten y con los de una fecha igual o posterior
                        $q->whereNull('fecha')->orWhere('repite', 1)->orWhere('fecha', '>=', $data['fecha']);
                    } else {
                        // el nuevo es de una sola fecha: choca con ese mismo día o con uno que ya se repetía desde antes
                        $q->where(function ($x) use ($data) {
                            $x->whereNull('fecha')
                              ->orWhere(fn ($y) => $y->where('repite', 1)->where('fecha', '<=', $data['fecha']));
                        })->orWhere(fn ($y) => $y->where('repite', 0)->where('fecha', $data['fecha']));
                    }
                })
                ->exists();
        };

        // Un terapeuta no puede tener dos horarios que se traslapen
        if ($traslape('id_usuario', $data['id_usuario'])) {
            abort(back()->withInput()->withErrors('El terapeuta ya tiene un horario que se empalma con esa fecha y hora.'));
        }
        // Un paciente tampoco puede estar en dos lugares a la vez
        if ($traslape('paciente', $data['paciente'])) {
            abort(back()->withInput()->withErrors('Ese paciente ya tiene un horario que se empalma con esa fecha y hora.'));
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
