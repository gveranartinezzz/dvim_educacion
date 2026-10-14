<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\InformeValoracion;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ValoracionController extends Controller
{
    use ConColumnas;

    private string $tabla = 'informes_valoracion';

    private array $campos = [
        'prueba_aplicada', 'aplicadora', 'motivo_evaluacion',
        'hc_gestacion', 'hc_talla_nacer', 'hc_diagnostico',
        'des_cognitivo', 'des_motor', 'des_social',
        'res_lenguaje', 'res_conducta_valoracion',
        'ind_escolares', 'ind_clinicas', 'ind_familiares', 'ind_rehabilitadores',
    ];

    public function index()
    {
        $pacientes = Paciente::query()->orderBy('nombre')->get();

        $q = InformeValoracion::query()->from($this->tabla . ' as v')->select('v.*');

        if (Schema::hasColumn($this->tabla, 'id_paciente')) {
            $q->leftJoin('pacientes as p', 'p.id', '=', 'v.id_paciente')
              ->addSelect('p.nombre as paciente_nombre');
        }

        $valoraciones = $q->orderByDesc('v.id')->get();

        return view('valoracion', compact('pacientes', 'valoraciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|integer',
            'prueba_aplicada' => 'required', 'aplicadora' => 'required', 'motivo_evaluacion' => 'required',
            'hc_diagnostico' => 'required', 'des_cognitivo' => 'required', 'des_motor' => 'required',
            'des_social' => 'required', 'res_lenguaje' => 'required', 'res_conducta_valoracion' => 'required',
        ]);

        $data = $request->only($this->campos);
        $data['id_paciente'] = $request->paciente_id;

        // Si la tabla guarda el nombre en texto en lugar del id
        if (Schema::hasColumn($this->tabla, 'paciente')) {
            $data['paciente'] = Paciente::query()->where('id', $request->paciente_id)->value('nombre');
        }
        if (Schema::hasColumn($this->tabla, 'id_usuario')) {
            $data['id_usuario'] = session('id_usuario');
        }

        InformeValoracion::query()->create($this->soloColumnas($this->tabla, $data));

        $omitidos = $this->sinColumna($this->tabla, $request->only($this->campos));
        $msg = 'Informe de valoración guardado.';
        if ($omitidos) {
            $msg .= ' (Sin columna en la BD, no se guardaron: ' . implode(', ', $omitidos) . ')';
        }

        return redirect('/valoracion')->with('ok', $msg);
    }
}
