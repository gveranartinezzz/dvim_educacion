<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\Avance;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AvanceController extends Controller
{
    use ConColumnas;

    private string $tabla = 'avances_pacientes';

    private array $campos = [
        'paciente_id', 'fecha_sesion', 'hora_inicio', 'hora_fin', 'estatus_sesion',
        'actividades_realizadas', 'observaciones_comportamiento',
        'logros_obtenidos', 'tareas_o_recomendaciones',
    ];

    public function index()
    {
        $pacientes = Paciente::query()->orderBy('nombre')->get();

        $q = Avance::query()->from($this->tabla . ' as a')
            ->leftJoin('pacientes as p', 'p.id', '=', 'a.paciente_id')
            ->select('a.*', 'p.nombre as paciente_nombre');

        if (Schema::hasColumn($this->tabla, 'usuario_id')) {
            $q->leftJoin('usuarios as u', 'u.id_usuario', '=', 'a.usuario_id')
              ->addSelect(DB::raw("CONCAT(u.nombre, ' ', u.apaterno) as especialista"));
        }

        $avances = $q->orderByDesc('a.id')->get();

        return view('avances', compact('pacientes', 'avances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id'                  => 'required|integer',
            'fecha_sesion'                 => 'required|date',
            'hora_inicio'                  => 'required',
            'hora_fin'                     => 'required',
            'estatus_sesion'               => 'required|string',
            'actividades_realizadas'       => 'required|string',
            'observaciones_comportamiento' => 'required|string',
            'logros_obtenidos'             => 'required|string',
            'tareas_o_recomendaciones'     => 'required|string',
        ]);

        $data = $request->only($this->campos);
        $data['usuario_id'] = session('id_usuario');
        $data['created_at'] = $data['updated_at'] = now();

        Avance::query()->create($this->soloColumnas($this->tabla, $data));

        return redirect('/avances')->with('ok', 'Avance diario guardado.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_sesion'   => 'required|date',
            'estatus_sesion' => 'required|string',
        ]);

        $data = $request->only(array_diff($this->campos, ['paciente_id']));
        $data['updated_at'] = now();

        Avance::query()->where('id', $id)->update($this->soloColumnas($this->tabla, $data));

        return redirect('/avances')->with('ok', 'Avance actualizado.');
    }
}
