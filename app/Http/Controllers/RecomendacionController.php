<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\Recomendacion;
use Illuminate\Support\Facades\DB;

class RecomendacionController extends Controller
{
    use ConColumnas;

    private array $campos = ['paciente', 'madre', 'padre', 'escuela_procedencia', 'grado', 'recomendacion', 'notas'];

    private array $reglas = [
        'paciente'      => 'required|string|max:150',
        'madre'         => 'required|string|max:150',
        'padre'         => 'required|string|max:150',
        'recomendacion' => 'required|string',
    ];

    public function index()
    {
        $recomendaciones = Recomendacion::query()->orderByDesc('id')->get();
        return view('recomendaciones', compact('recomendaciones'));
    }

    public function store(Request $request)
    {
        $request->validate($this->reglas);
        Recomendacion::query()->create($request->only($this->campos));
        return redirect('/recomendaciones')->with('ok', 'Recomendación guardada.');
    }

    public function edit($id)
    {
        $rec = Recomendacion::query()->where('id', $id)->first();
        abort_unless($rec, 404);
        return view('editar_recomendacion', compact('rec'));
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->reglas);
        Recomendacion::query()->where('id', $id)->update($request->only($this->campos));
        return redirect('/recomendaciones')->with('ok', 'Recomendación actualizada.');
    }

    public function destroy($id)
    {
        return $this->borrar(Recomendacion::class, 'id', $id, '/recomendaciones', 'Recomendación eliminada.');
    }
}
