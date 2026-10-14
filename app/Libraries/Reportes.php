<?php

namespace App\Libraries;

/**
 * Arma cada PDF del sistema a partir de un arreglo de datos.
 * Cada método devuelve el contenido binario del PDF.
 */
class Reportes
{
    private static function nuevo(string $titulo): DivmPdf
    {
        $logo = function_exists('public_path') ? public_path('img/logo.png') : dirname(__DIR__, 2) . '/public/img/logo.png';
        $pdf = new DivmPdf($titulo, $logo);
        $pdf->AddPage();
        return $pdf;
    }

    private static function fecha($f): string
    {
        return $f ? date('d/m/Y', strtotime((string) $f)) : '';
    }

    /** Informe pedagógico (tabla informes). */
    public static function informe(array $d): string
    {
        $pdf = self::nuevo('INFORME PEDAGÓGICO');

        $pdf->seccion('DATOS DE IDENTIFICACIÓN');
        $pdf->fila('PACIENTE:', $d['paciente'] ?? '');
        $pdf->fila('FECHA DE NACIMIENTO:', self::fecha($d['fecha_nacimiento'] ?? null));
        $pdf->fila('DOMICILIO:', $d['domicilio'] ?? '');

        $pdf->seccion('ENTORNO FAMILIAR');
        $pdf->fila('NOMBRE DEL PADRE:', trim(($d['nombre_padre'] ?? '') . ($d['edad_padre'] ? ' (edad: ' . $d['edad_padre'] . ')' : '')));
        $pdf->fila('ESTUDIOS / OCUPACIÓN:', $d['grado_estudios_padre'] ?? '');
        $pdf->Ln(1);
        $pdf->fila('NOMBRE DE LA MADRE:', trim(($d['nombre_madre'] ?? '') . ($d['edad_madre'] ? ' (edad: ' . $d['edad_madre'] . ')' : '')));
        $pdf->fila('ESTUDIOS / OCUPACIÓN:', $d['grado_estudios_madre'] ?? '');

        $pdf->seccion('EVALUACIÓN Y MOTIVO');
        $pdf->fila('PRUEBA APLICADA:', $d['prueba_aplicada'] ?? '');
        $pdf->fila('APLICADORA:', $d['aplicadora'] ?? '');
        $pdf->fila('MOTIVO DE EVALUACIÓN:', $d['motivo_evaluacion'] ?? '');

        $pdf->seccion('HISTORIA CLÍNICA');
        $pdf->fila('GESTACIÓN:', $d['hc_gestacion'] ?? '');
        $pdf->fila('TALLA / PESO AL NACER:', $d['hc_talla_nacer'] ?? '');
        $pdf->fila('DIAGNÓSTICO:', $d['hc_diagnostico'] ?? '');

        $pdf->seccion('RESULTADOS POR ÁREA');
        $pdf->bloque('DESARROLLO COGNITIVO:', $d['des_cognitivo'] ?? '');
        $pdf->bloque('DESARROLLO MOTOR:', $d['des_motor'] ?? '');
        $pdf->bloque('DESARROLLO SOCIAL:', $d['des_social'] ?? '');
        $pdf->bloque('LENGUAJE Y COMUNICACIÓN:', $d['res_lenguaje'] ?? '');
        $pdf->bloque('CONDUCTA EN VALORACIÓN:', $d['res_conducta_valoracion'] ?? '');

        $pdf->seccion('INDICACIONES Y RECOMENDACIONES');
        $pdf->bloque('ESCOLARES:', $d['ind_escolares'] ?? '');
        $pdf->bloque('CLÍNICAS:', $d['ind_clinicas'] ?? '');
        $pdf->bloque('FAMILIARES:', $d['ind_familiares'] ?? '');
        $pdf->bloque('REHABILITADORES:', $d['ind_rehabilitadores'] ?? '');

        return $pdf->Output('S');
    }

    /** Informe de valoración (tabla informes_valoracion + nombre del paciente). */
    public static function valoracion(array $d): string
    {
        $pdf = self::nuevo('INFORME DE VALORACIÓN');

        $pdf->seccion('DATOS GENERALES');
        $pdf->fila('PACIENTE:', $d['paciente_nombre'] ?? '');
        $pdf->fila('FECHA DEL INFORME:', self::fecha($d['fecha_registro'] ?? null));
        $pdf->fila('PRUEBA APLICADA:', $d['prueba_aplicada'] ?? '');
        $pdf->fila('APLICADORA:', $d['aplicadora'] ?? '');
        $pdf->fila('MOTIVO DE EVALUACIÓN:', $d['motivo_evaluacion'] ?? '');

        $pdf->seccion('HISTORIA CLÍNICA');
        $pdf->fila('GESTACIÓN:', $d['hc_gestacion'] ?? '');
        $pdf->fila('TALLA / PESO AL NACER:', $d['hc_talla_nacer'] ?? '');
        $pdf->fila('DIAGNÓSTICO:', $d['hc_diagnostico'] ?? '');

        $pdf->seccion('RESULTADOS POR ÁREA');
        $pdf->bloque('DESARROLLO COGNITIVO:', $d['des_cognitivo'] ?? '');
        $pdf->bloque('DESARROLLO MOTOR:', $d['des_motor'] ?? '');
        $pdf->bloque('DESARROLLO SOCIAL:', $d['des_social'] ?? '');
        $pdf->bloque('LENGUAJE Y COMUNICACIÓN:', $d['res_lenguaje'] ?? '');
        $pdf->bloque('CONDUCTA EN VALORACIÓN:', $d['res_conducta_valoracion'] ?? '');

        $pdf->seccion('INDICACIONES Y RECOMENDACIONES');
        $pdf->bloque('ESCOLARES:', $d['ind_escolares'] ?? '');
        $pdf->bloque('CLÍNICAS:', $d['ind_clinicas'] ?? '');
        $pdf->bloque('FAMILIARES:', $d['ind_familiares'] ?? '');
        $pdf->bloque('REHABILITADORES:', $d['ind_rehabilitadores'] ?? '');

        return $pdf->Output('S');
    }

    /** Avance diario de una sesión. */
    public static function avance(array $d): string
    {
        $pdf = self::nuevo('REPORTE DE EVOLUCIÓN Y AVANCE DIARIO');

        $hi = !empty($d['hora_inicio']) ? substr($d['hora_inicio'], 0, 5) : '';
        $hf = !empty($d['hora_fin']) ? substr($d['hora_fin'], 0, 5) : '';

        $pdf->seccion('DATOS DE LA SESIÓN');
        $pdf->fila('ALUMNO / PACIENTE:', $d['paciente'] ?? '');
        $pdf->fila('FECHA DE SESIÓN:', self::fecha($d['fecha_sesion'] ?? null));
        $pdf->fila('HORARIO:', $hi && $hf ? "$hi a $hf hrs" : '');
        $pdf->fila('ESPECIALISTA:', $d['especialista'] ?? '');
        $pdf->fila('ESTATUS DE LA SESIÓN:', $d['estatus_sesion'] ?? '');

        $pdf->seccion('EVALUACIÓN DE ÁREAS Y EVOLUCIÓN');
        $pdf->bloque('1. ACTIVIDADES Y ÁREAS TRABAJADAS:', $d['actividades_realizadas'] ?? '');
        $pdf->bloque('2. OBSERVACIONES CONDUCTUALES Y ESTADO EMOCIONAL:', $d['observaciones_comportamiento'] ?? '');
        $pdf->bloque('3. LOGROS OBTENIDOS Y NIVEL DE APOYO:', $d['logros_obtenidos'] ?? '');
        $pdf->bloque('4. TAREAS, RECOMENDACIONES O AJUSTES PARA EL HOGAR:', $d['tareas_o_recomendaciones'] ?? '');

        return $pdf->Output('S');
    }

    /** Plan de intervención para padres. */
    public static function plan(array $d): string
    {
        $pdf = self::nuevo('PLAN DE INTERVENCIÓN PARA PADRES');

        $pdf->seccion('IDENTIFICACIÓN DEL PACIENTE');
        $pdf->fila('PACIENTE:', $d['paciente'] ?? '');
        $pdf->fila('PADRE:', $d['padre'] ?? '');
        $pdf->fila('MADRE:', $d['madre'] ?? '');

        $pdf->seccion('ÁREAS DE INTERVENCIÓN ESPECIALIZADA');
        $pdf->bloque('TERAPIA COMUNICATIVA:', $d['terapia_comunicativa'] ?? '');
        $pdf->bloque('TERAPIA DE INTEGRACIÓN SENSORIAL:', $d['terapia_sensorial'] ?? '');
        $pdf->bloque('TERAPIA DE APRENDIZAJE:', $d['terapia_aprendizaje'] ?? '');
        $pdf->bloque('TERAPIA OCUPACIONAL:', $d['terapia_ocupacional'] ?? '');
        $pdf->bloque('TERAPIA DE ATENCIÓN Y CONCENTRACIÓN:', $d['terapia_atencion_concentracion'] ?? '');
        $pdf->bloque('TERAPIA DE EDUCACIÓN EMOCIONAL:', $d['terapia_educacion_emocional'] ?? '');

        return $pdf->Output('S');
    }

    /** Recomendación escolar. */
    public static function recomendacion(array $d): string
    {
        $pdf = self::nuevo('RECOMENDACIONES ESCOLARES');

        $pdf->seccion('DATOS GENERALES');
        $pdf->fila('PACIENTE:', $d['paciente'] ?? '');
        $pdf->fila('PADRE:', $d['padre'] ?? '');
        $pdf->fila('MADRE:', $d['madre'] ?? '');
        $pdf->fila('ESCUELA DE PROCEDENCIA:', $d['escuela_procedencia'] ?? '');
        $pdf->fila('GRADO:', $d['grado'] ?? '');
        $pdf->fila('FECHA:', self::fecha($d['fecha_creacion'] ?? null));

        $pdf->seccion('RECOMENDACIÓN');
        $pdf->bloque('', $d['recomendacion'] ?? '');
        $pdf->seccion('NOTAS ADICIONALES');
        $pdf->bloque('', $d['notas'] ?? '');

        return $pdf->Output('S');
    }
}
