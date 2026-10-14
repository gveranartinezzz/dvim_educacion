<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\Informe;
use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
    use ConColumnas;

    private array $campos = [
        'paciente', 'nombre_padre', 'grado_estudios_padre', 'edad_padre',
        'nombre_madre', 'grado_estudios_madre', 'edad_madre',
        'fecha_nacimiento', 'domicilio',
        'prueba_aplicada', 'aplicadora', 'motivo_evaluacion',
        'hc_gestacion', 'hc_talla_nacer', 'hc_diagnostico',
        'res_lenguaje', 'res_conducta_valoracion',
        'des_cognitivo', 'des_motor', 'des_social',
        'ind_clinicas', 'ind_familiares', 'ind_escolares', 'ind_rehabilitadores',
    ];

    public function index()
    {
        $informes = Informe::query()->orderByDesc('id')->get();
        return view('informe', compact('informes'));
    }

    public function store(Request $request)
    {
        $request->validate(['paciente' => 'required|string|max:150']);
        Informe::query()->create($request->only($this->campos));
        return redirect('/informe')->with('ok', 'Informe guardado.');
    }

    /** Datos para la gráfica de diagnósticos (JSON). */
    public function grafica()
    {
        $datos = Informe::query()
            ->selectRaw("IFNULL(NULLIF(hc_diagnostico, ''), 'Sin diagnóstico') AS diagnostico, COUNT(*) AS total")
            ->groupBy('hc_diagnostico')
            ->orderByDesc('total')
            ->get();

        return response()->json($datos);
    }

    public function edit($id)
    {
        $inf = Informe::query()->where('id', $id)->first();
        abort_unless($inf, 404);
        return view('editar_informe', compact('inf'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['paciente' => 'required|string|max:150']);
        Informe::query()->where('id', $id)->update($request->only($this->campos));
        return redirect('/informe')->with('ok', 'Informe actualizado.');
    }

    public function destroy($id)
    {
        return $this->borrar(Informe::class, 'id', $id, '/informe', 'Informe eliminado.');
    }
}
