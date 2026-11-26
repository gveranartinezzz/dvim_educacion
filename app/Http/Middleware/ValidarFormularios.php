<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Validación central de TODOS los formularios (se ejecuta antes de cada controlador).
 * Cada regla lleva "sometimes": solo se revisa un campo si el formulario lo trae.
 * Los controladores siguen aplicando sus propias reglas (p. ej. que el paciente exista).
 * Si algo no cumple, se regresa al formulario con el mensaje en español y lo escrito se conserva.
 */
class ValidarFormularios
{
    private const NOMBRE = '/^[\pL\pM][\pL\pM\s\.\'’\-]*$/u';
    private const CLAVE  = '/^(?=.*[A-Za-z])(?=.*\d).+$/';

    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'], true)) {
            foreach ($this->tabla() as [$rutas, $reglas, $mensajes]) {
                if (preg_match($rutas, '/' . ltrim($request->path(), '/'))) {
                    Validator::make($request->all(), $reglas, $mensajes)->validate();
                }
            }
        }
        return $next($request);
    }

    private function tabla(): array
    {
        $texto = fn (int $max) => "sometimes|nullable|string|max:$max";
        $persona = 'sometimes|nullable|string|max:150|regex:' . self::NOMBRE;
        $msgNombre = ['regex' => 'El campo :attribute solo puede llevar letras, espacios, puntos y guiones (sin números).'];
        $msgClave  = ['regex' => 'La contraseña debe incluir al menos una letra y un número.'];

        return [
            // Pacientes
            ['#^/registro(/\d+)?$#', [
                'nombre'           => 'sometimes|required|string|min:3|max:150|regex:' . self::NOMBRE,
                'edad'             => 'sometimes|required|integer|between:0,120',
                'fecha_nacimiento' => 'sometimes|nullable|date|after:1900-01-01|before_or_equal:today',
                'cp'               => 'sometimes|nullable|digits:5',
                'calle'            => $texto(150), 'colonia' => $texto(100), 'municipio' => $texto(100),
                'referencias'      => $texto(255), 'direccion' => $texto(500),
                'escolarizado'     => 'sometimes|nullable|in:Sí,No',
                'diagnostico'      => $texto(150), 'motivo_consulta' => $texto(1000),
                'madre'            => $persona, 'padre' => $persona,
            ], $msgNombre],

            // Planes
            ['#^/plan(/\d+)?$#', [
                'madre' => $persona, 'padre' => $persona,
                'terapia_comunicativa' => $texto(5000), 'terapia_sensorial' => $texto(5000),
                'terapia_aprendizaje' => $texto(5000), 'terapia_ocupacional' => $texto(5000),
                'terapia_atencion' => $texto(5000), 'terapia_atencion_concentracion' => $texto(5000),
                'terapia_emocional' => $texto(5000), 'terapia_educacion_emocional' => $texto(5000),
            ], $msgNombre],

            // Recomendaciones
            ['#^/recomendaciones(/\d+)?$#', [
                'madre' => $persona, 'padre' => $persona,
                'escuela_procedencia' => $texto(255),
                'grado'         => 'sometimes|nullable|in:Estancia,Preescolar,Primaria,Secundaria,Preparatoria,Universidad',
                'recomendacion' => 'sometimes|required|string|min:3|max:5000',
                'notas'         => $texto(2000),
            ], $msgNombre],

            // Informes
            ['#^/informe(/\d+)?$#', [
                'nombre_padre' => $persona, 'nombre_madre' => $persona,
                'edad_padre' => 'sometimes|nullable|integer|between:12,100', 'edad_madre' => 'sometimes|nullable|integer|between:12,100',
                'fecha_nacimiento' => 'sometimes|nullable|date|after:1900-01-01|before_or_equal:today',
                'prueba_aplicada' => $texto(255), 'aplicadora' => $texto(255), 'motivo_evaluacion' => $texto(3000),
                'domicilio' => $texto(500),
            ] + $this->areas(5000), $msgNombre],

            // Valoración
            ['#^/valoracion(/\d+)?$#', [
                'paciente_id' => 'sometimes|required|integer|exists:pacientes,id',
                'prueba_aplicada' => 'sometimes|required|string|max:255',
                'aplicadora' => 'sometimes|required|string|max:255',
                'motivo_evaluacion' => 'sometimes|required|string|max:3000',
                'hc_talla_nacer' => $texto(100),
            ] + $this->areas(5000), []],

            // Avances (sesiones)
            ['#^/avances(/\d+)?$#', [
                'paciente_id' => 'sometimes|required|integer|exists:pacientes,id',
                'fecha_sesion' => 'sometimes|required|date|after:2000-01-01|before_or_equal:today',
                'hora_fin' => 'sometimes|required|after:hora_inicio',
                'estatus_sesion' => 'sometimes|required|in:Asistió,Falta Justificada,Falta Injustificada,Cancelada',
                'actividades_realizadas' => 'sometimes|required|string|max:3000',
                'observaciones_comportamiento' => 'sometimes|required|string|max:3000',
                'logros_obtenidos' => 'sometimes|required|string|max:3000',
                'tareas_o_recomendaciones' => 'sometimes|required|string|max:3000',
            ], ['hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.']],

            // Usuarios (alta, edición y "Crear cuenta" desde el inicio)
            ['#^/(usuarios(/\d+)?|crear-cuenta)$#', [
                'nombre' => 'sometimes|required|string|min:2|max:100|regex:' . self::NOMBRE,
                'apaterno' => 'sometimes|required|string|min:2|max:100|regex:' . self::NOMBRE,
                'amaterno' => 'sometimes|required|string|min:2|max:100|regex:' . self::NOMBRE,
                'correo' => 'sometimes|required|email:rfc|max:100',
                'contrasena' => 'sometimes|required|string|min:8|max:72|regex:' . self::CLAVE,
                'nueva_contrasena' => 'sometimes|nullable|string|min:8|max:72|regex:' . self::CLAVE,
            ], $msgNombre + $msgClave],

            // Horarios
            ['#^/distribucion(/\d+)?$#', [
                'dia_semana' => 'sometimes|required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
                'hora_fin' => 'sometimes|required|after:hora_inicio',
            ], ['hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.']],
        ];
    }

    /** Campos de texto largo comunes a Informe y Valoración. */
    private function areas(int $max): array
    {
        $r = [];
        foreach (['hc_gestacion', 'hc_diagnostico', 'des_cognitivo', 'des_motor', 'des_social', 'res_lenguaje',
                  'res_conducta_valoracion', 'ind_escolares', 'ind_clinicas', 'ind_familiares', 'ind_rehabilitadores'] as $c) {
            $r[$c] = "sometimes|nullable|string|max:$max";
        }
        return $r;
    }
}
