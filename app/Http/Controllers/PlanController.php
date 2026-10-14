<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    use ConColumnas;

    public function index()
    {
        $planes = Plan::query()->orderByDesc('id')->get();
        return view('plan', compact('planes'));
    }

    public function store(Request $request)
    {
        $request->validate(['paciente' => 'required|string|max:150']);

        Plan::query()->create([
            'paciente'                       => $request->paciente,
            'padre'                          => $request->padre,
            'madre'                          => $request->madre,
            'terapia_comunicativa'           => $request->terapia_comunicativa,
            'terapia_sensorial'              => $request->terapia_sensorial,
            'terapia_aprendizaje'            => $request->terapia_aprendizaje,
            'terapia_ocupacional'            => $request->terapia_ocupacional,
            // En el formulario se llaman distinto que en la BD
            'terapia_atencion_concentracion' => $request->terapia_atencion,
            'terapia_educacion_emocional'    => $request->terapia_emocional,
        ]);

        return redirect('/plan')->with('ok', 'Plan de intervención guardado.');
    }

    public function edit($id)
    {
        $plan = Plan::query()->where('id', $id)->first();
        abort_unless($plan, 404);
        return view('editar_plan', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'paciente' => 'required|string|max:150',
            'padre'    => 'required|string|max:150',
            'madre'    => 'required|string|max:150',
        ]);

        Plan::query()->where('id', $id)->update($request->only([
            'paciente', 'padre', 'madre',
            'terapia_comunicativa', 'terapia_sensorial', 'terapia_aprendizaje',
            'terapia_ocupacional', 'terapia_atencion_concentracion', 'terapia_educacion_emocional',
        ]));

        return redirect('/plan')->with('ok', 'Plan actualizado.');
    }

    public function destroy($id)
    {
        return $this->borrar(Plan::class, 'id', $id, '/plan', 'Plan eliminado.');
    }
}
