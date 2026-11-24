<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CitaController extends Controller
{
    private const ESTATUS = ['Programada', 'Realizada', 'Cancelada', 'No asistió'];

    private function asegurarTabla(): void
    {
        if (Schema::hasTable('citas')) return;
        Schema::create('citas', function ($t) {
            $t->id();
            $t->string('paciente', 150);
            $t->unsignedBigInteger('id_usuario')->nullable();
            $t->date('fecha');
            $t->time('hora_inicio');
            $t->time('hora_fin');
            $t->string('motivo', 255);
            $t->text('notas')->nullable();
            $t->string('estatus', 20)->default('Programada');
            $t->timestamps();
            $t->index(['fecha', 'hora_inicio']);
        });
    }

    public function index(Request $r)
    {
        $this->asegurarTabla();
        $mes = preg_match('/^\d{4}-\d{2}$/', (string) $r->query('mes')) ? $r->query('mes') : now()->format('Y-m');
        $ini = Carbon::createFromFormat('Y-m-d', $mes . '-01')->startOfDay();
        $fin = $ini->copy()->endOfMonth();

        $citas = Cita::query()->from('citas as c')
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'c.id_usuario')
            ->whereBetween('c.fecha', [$ini->toDateString(), $fin->toDateString()])
            ->orderBy('c.fecha')->orderBy('c.hora_inicio')
            ->get(['c.*', DB::raw("TRIM(CONCAT(COALESCE(u.nombre,''),' ',COALESCE(u.apaterno,''))) as terapeuta")]);

        $proximas = Cita::query()->from('citas as c')
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'c.id_usuario')
            ->where('c.fecha', '>=', now()->toDateString())->where('c.estatus', 'Programada')
            ->orderBy('c.fecha')->orderBy('c.hora_inicio')->limit(10)
            ->get(['c.*', DB::raw("TRIM(CONCAT(COALESCE(u.nombre,''),' ',COALESCE(u.apaterno,''))) as terapeuta")]);

        return view('citas', [
            'ini' => $ini,
            'citasPorDia' => $citas->groupBy('fecha'),
            'proximas' => $proximas,
            'terapeutas' => Usuario::query()->where('estado', 'Activo')->orderBy('nombre')->get(),
            'estatus' => self::ESTATUS,
            'editar' => $r->query('editar') ? Cita::find($r->query('editar')) : null,
            'diaSel' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $r->query('dia')) ? $r->query('dia') : null,
        ]);
    }

    private function datos(Request $r, ?int $id = null): array
    {
        $d = $r->validate([
            'paciente'    => 'required|string|max:150|exists:pacientes,nombre',
            'id_usuario'  => 'required|integer|exists:usuarios,id_usuario',
            'fecha'       => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
            'motivo'      => 'required|string|min:3|max:255',
            'notas'       => 'nullable|string|max:1000',
            'estatus'     => 'nullable|in:' . implode(',', self::ESTATUS),
        ], [
            'paciente.exists'   => 'Elige un paciente de la lista.',
            'hora_fin.after'    => 'La hora de fin debe ser posterior a la hora de inicio.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener formato 24 horas, por ejemplo 16:00.',
            'hora_fin.date_format'    => 'La hora de fin debe tener formato 24 horas, por ejemplo 17:00.',
        ]);
        if (!$id && $d['fecha'] < now()->toDateString()) {
            abort(back()->withInput()->withErrors(['fecha' => 'No se pueden agendar citas en días pasados.']));
        }
        $d['estatus'] = $d['estatus'] ?? 'Programada';

        if ($d['estatus'] !== 'Cancelada') {
            $choque = fn ($campo, $valor) => Cita::where('fecha', $d['fecha'])
                ->where('estatus', '!=', 'Cancelada')->where($campo, $valor)
                ->where('hora_inicio', '<', $d['hora_fin'])->where('hora_fin', '>', $d['hora_inicio'])
                ->when($id, fn ($q) => $q->where('id', '!=', $id))->exists();
            if ($choque('id_usuario', $d['id_usuario'])) {
                abort(back()->withInput()->withErrors(['hora_inicio' => 'Ese terapeuta ya tiene una cita en ese horario.']));
            }
            if ($choque('paciente', $d['paciente'])) {
                abort(back()->withInput()->withErrors(['hora_inicio' => 'Ese paciente ya tiene una cita en ese horario.']));
            }
        }
        return $d;
    }

    public function store(Request $r)
    {
        $this->asegurarTabla();
        $d = $this->datos($r);
        Cita::create($d);
        return redirect('/citas?mes=' . substr($d['fecha'], 0, 7))->with('success', 'Cita agendada correctamente.');
    }

    public function update(Request $r, $id)
    {
        $this->asegurarTabla();
        $cita = Cita::findOrFail($id);
        $d = $this->datos($r, (int) $id);
        $cita->update($d);
        return redirect('/citas?mes=' . substr($d['fecha'], 0, 7))->with('success', 'Cita actualizada.');
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $mes = substr((string) $cita->fecha, 0, 7);
        $cita->delete();
        return redirect('/citas?mes=' . $mes)->with('success', 'Cita eliminada.');
    }
}
