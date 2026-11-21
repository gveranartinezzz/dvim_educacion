<?php

namespace App\Http\Controllers;

use App\Models\Avance;
use App\Models\Horario;
use App\Models\Informe;
use App\Models\InformeValoracion;
use App\Models\Paciente;
use App\Models\Plan;
use App\Models\Recomendacion;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Panel con resumen, expediente por paciente, exportación a Excel (CSV) y bitácora. */
class MejorasController extends Controller
{
    private function contar(string $modelo): int
    {
        try { return $modelo::query()->count(); } catch (\Throwable $e) { return 0; }
    }

    public function panel()
    {
        $pacientes = collect();
        try { $pacientes = Paciente::query()->get(); } catch (\Throwable $e) {}

        $activos = $pacientes->filter(fn ($p) => ($p->estatus ?? 'Activo') !== 'Inactivo');

        $porDiagnostico = $activos->groupBy(fn ($p) => trim((string) $p->diagnostico) ?: 'Sin diagnóstico')
            ->map->count()->sortDesc()->take(6);

        $porEdad = [
            '0 a 5'   => $activos->filter(fn ($p) => is_numeric($p->edad) && $p->edad <= 5)->count(),
            '6 a 11'  => $activos->filter(fn ($p) => is_numeric($p->edad) && $p->edad >= 6 && $p->edad <= 11)->count(),
            '12 a 17' => $activos->filter(fn ($p) => is_numeric($p->edad) && $p->edad >= 12 && $p->edad <= 17)->count(),
            '18 o más' => $activos->filter(fn ($p) => is_numeric($p->edad) && $p->edad >= 18)->count(),
        ];

        $sinPlan = [];
        try {
            $conPlan = Plan::query()->pluck('paciente')->all();
            $sinPlan = $activos->pluck('nombre')->reject(fn ($n) => in_array($n, $conPlan, true))->values()->take(8)->all();
        } catch (\Throwable $e) {}

        $pendientes = 0;
        try { $pendientes = Usuario::query()->where('estado', 'Inactivo')->count(); } catch (\Throwable $e) {}

        return view('panel', [
            'totales' => [
                'Pacientes activos' => $activos->count(),
                'Planes'            => $this->contar(Plan::class),
                'Valoraciones'      => $this->contar(InformeValoracion::class),
                'Avances'           => $this->contar(Avance::class),
                'Recomendaciones'   => $this->contar(Recomendacion::class),
                'Informes'          => $this->contar(Informe::class),
            ],
            'porDiagnostico' => $porDiagnostico,
            'porEdad'        => $porEdad,
            'sinPlan'        => $sinPlan,
            'pendientes'     => $pendientes,
            'esAdmin'        => session('rol') === 'Administrador',
        ]);
    }

    public function expediente(?string $nombre = null)
    {
        $lista = Paciente::query()->orderBy('nombre')->get();
        $paciente = $nombre ? $lista->firstWhere('nombre', $nombre) : null;
        if ($nombre && !$paciente) {
            abort(404, 'Paciente no encontrado.');
        }

        $secciones = [];
        if ($paciente) {
            $secciones = [
                'Planes de trabajo'  => Plan::query()->where('paciente', $paciente->nombre)->get(),
                'Valoraciones'       => InformeValoracion::query()->where('id_paciente', $paciente->id)->get(),
                'Avances'            => Avance::query()->where('paciente_id', $paciente->id)->get(),
                'Recomendaciones'    => Recomendacion::query()->where('paciente', $paciente->nombre)->get(),
                'Informes'           => Informe::query()->where('paciente', $paciente->nombre)->get(),
                'Horarios'           => Horario::query()->where('paciente', $paciente->nombre)->get(),
            ];
        }

        return view('expediente', compact('lista', 'paciente', 'secciones'));
    }

    /** Excel abre este CSV directo (UTF-8 con BOM). Solo lista de pacientes: sin contraseñas ni datos de usuarios. */
    public function exportarPacientes()
    {
        $filas = Paciente::query()->orderBy('nombre')->get();
        $cols = ['nombre', 'fecha_nacimiento', 'edad', 'diagnostico', 'estatus', 'motivo_consulta', 'escolarizado', 'madre', 'padre', 'direccion'];

        return response()->streamDownload(function () use ($filas, $cols) {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF");
            fputcsv($f, array_map(fn ($c) => ucfirst(str_replace('_', ' ', $c)), $cols));
            foreach ($filas as $p) {
                // Evita que Excel ejecute fórmulas escritas en un texto (=, +, -, @)
                fputcsv($f, array_map(function ($c) use ($p) {
                    $v = (string) $p->{$c};
                    return preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v;
                }, $cols));
            }
            fclose($f);
        }, 'pacientes_' . date('Y-m-d') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function bitacora()
    {
        $registros = collect();
        if (Schema::hasTable('bitacora')) {
            $registros = DB::table('bitacora')->orderByDesc('id')->limit(200)->get();
        }
        return view('bitacora', ['registros' => $registros, 'existe' => Schema::hasTable('bitacora')]);
    }
}
