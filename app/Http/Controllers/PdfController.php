<?php

namespace App\Http\Controllers;

use App\Libraries\Reportes;
use App\Models\Avance;
use App\Models\Informe;
use App\Models\InformeValoracion;
use App\Models\Plan;
use App\Models\Recomendacion;

/** Genera los PDF del sistema (se abren en una pestaña nueva del navegador). */
class PdfController extends Controller
{
    private function pdf(string $contenido, string $nombre)
    {
        $nombre = preg_replace('/[^A-Za-z0-9_\-]/', '_', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombre) ?: 'documento');

        return response($contenido, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombre . '.pdf"',
        ]);
    }

    public function informe($id)
    {
        $d = Informe::query()->where('id', $id)->first();
        abort_unless($d, 404, 'Informe no encontrado.');
        return $this->pdf(Reportes::informe($d->toArray()), 'Informe_' . $d->paciente);
    }

    public function valoracion($id)
    {
        $d = InformeValoracion::query()
            ->from('informes_valoracion as v')
            ->leftJoin('pacientes as p', 'p.id', '=', 'v.id_paciente')
            ->where('v.id', $id)
            ->select('v.*', 'p.nombre as paciente_nombre')
            ->first();
        abort_unless($d, 404, 'Valoración no encontrada.');
        return $this->pdf(Reportes::valoracion($d->toArray()), 'Valoracion_' . $d->paciente_nombre);
    }

    public function avance($id)
    {
        $d = Avance::query()
            ->from('avances_pacientes as a')
            ->leftJoin('pacientes as p', 'p.id', '=', 'a.paciente_id')
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'a.usuario_id')
            ->where('a.id', $id)
            ->select('a.*', 'p.nombre as paciente', \Illuminate\Support\Facades\DB::raw("CONCAT(u.nombre, ' ', u.apaterno) as especialista"))
            ->first();
        abort_unless($d, 404, 'Avance no encontrado.');
        return $this->pdf(Reportes::avance($d->toArray()), 'Avance_' . $d->paciente);
    }

    public function plan($id)
    {
        $d = Plan::query()->where('id', $id)->first();
        abort_unless($d, 404, 'Plan no encontrado.');
        return $this->pdf(Reportes::plan($d->toArray()), 'Plan_' . $d->paciente);
    }

    public function recomendacion($id)
    {
        $d = Recomendacion::query()->where('id', $id)->first();
        abort_unless($d, 404, 'Recomendación no encontrada.');
        return $this->pdf(Reportes::recomendacion($d->toArray()), 'Recomendacion_' . $d->paciente);
    }
}
