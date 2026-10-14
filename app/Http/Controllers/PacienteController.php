<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ConColumnas;
use Illuminate\Http\Request;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    use ConColumnas;

    public function index()
    {
        $pacientes = Paciente::query()->orderBy('nombre')->get();
        return view('registro', compact('pacientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:150',
            'fecha_nacimiento' => 'nullable|date',
            'edad'             => 'required|integer|min:0',
            'calle'            => 'nullable|string|max:150',
            'cp'               => 'nullable|string|max:5',
            'colonia'          => 'nullable|string|max:100',
            'municipio'        => 'nullable|string|max:100',
            'referencias'      => 'nullable|string|max:255',
            'escolarizado'     => 'nullable|in:Sí,No',
            'diagnostico'      => 'nullable|string|max:150',
            'motivo_consulta'  => 'nullable|string',
            'madre'            => 'nullable|string|max:150',
            'padre'            => 'nullable|string|max:150',
        ]);

        $direccion = trim(implode(', ', array_filter([
            $data['calle'] ?? null,
            $data['colonia'] ?? null,
            $data['municipio'] ?? null,
            !empty($data['cp']) ? 'C.P. ' . $data['cp'] : null,
            !empty($data['referencias']) ? 'Ref: ' . $data['referencias'] : null,
        ])));

        Paciente::query()->create([
            'nombre'           => $data['nombre'],
            'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
            'edad'             => $data['edad'],
            'direccion'        => $direccion,
            'escolarizado'     => $data['escolarizado'] ?? 'No',
            'diagnostico'      => $data['diagnostico'] ?? null,
            'motivo_consulta'  => $data['motivo_consulta'] ?? null,
            'madre'            => $data['madre'] ?? null,
            'padre'            => $data['padre'] ?? null,
        ]);

        return redirect('/registro')->with('ok', 'Paciente registrado correctamente.');
    }

    public function edit($id)
    {
        $paciente = Paciente::query()->where('id', $id)->first();
        abort_unless($paciente, 404);
        return view('editar_registro', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:150',
            'fecha_nacimiento' => 'nullable|date',
            'edad'             => 'required|integer|min:0',
            'direccion'        => 'nullable|string',
            'escolarizado'     => 'nullable|in:Sí,No',
            'diagnostico'      => 'nullable|string|max:150',
            'motivo_consulta'  => 'nullable|string',
            'madre'            => 'nullable|string|max:150',
            'padre'            => 'nullable|string|max:150',
        ]);

        Paciente::query()->where('id', $id)->update($data);
        return redirect('/registro')->with('ok', 'Expediente actualizado.');
    }

    /** Alta / baja: cambia el estatus del paciente entre Activo e Inactivo. */
    public function estatus($id)
    {
        $paciente = Paciente::query()->where('id', $id)->first();
        abort_unless($paciente, 404);

        $nuevo = $paciente->estatus === 'Activo' ? 'Inactivo' : 'Activo';
        Paciente::query()->where('id', $id)->update(['estatus' => $nuevo]);

        return redirect('/registro')->with('ok', 'Paciente ' . ($nuevo === 'Activo' ? 'dado de alta.' : 'dado de baja.'));
    }

    public function destroy($id)
    {
        return $this->borrar(Paciente::class, 'id', $id, '/registro', 'Paciente eliminado.');
    }
}
