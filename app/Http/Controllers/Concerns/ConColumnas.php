<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ConColumnas
{
    /** Deja solo los campos que existen como columna en la tabla. */
    protected function soloColumnas(string $tabla, array $data): array
    {
        return array_intersect_key($data, array_flip(Schema::getColumnListing($tabla)));
    }

    /** Campos del formulario que NO tienen columna en la tabla. */
    protected function sinColumna(string $tabla, array $data): array
    {
        return array_values(array_diff(array_keys($data), Schema::getColumnListing($tabla)));
    }

    /**
     * Regla para el campo "paciente": debe ser uno de los pacientes registrados en Pacientes.
     * $actual = valor que ya tenía el registro al editarlo (se acepta aunque sea un nombre antiguo).
     */
    protected function reglaPaciente(?string $actual = null): array
    {
        return ['required', 'string', 'max:150', function ($atributo, $valor, $fallo) use ($actual) {
            if ($actual !== null && $valor === $actual) {
                return;
            }
            if (!\App\Models\Paciente::query()->where('nombre', $valor)->exists()) {
                $fallo('Elige un paciente de la lista. Si no aparece, primero regístralo en Pacientes.');
            }
        }];
    }

    /** Elimina un registro del modelo indicado y vuelve a la lista con un mensaje. */
    protected function borrar(string $modelo, string $pk, $id, string $ruta, string $msg)
    {
        try {
            $modelo::query()->where($pk, $id)->delete();
            return redirect($ruta)->with('ok', $msg);
        } catch (\Throwable $e) {
            return redirect($ruta)->withErrors('No se pudo eliminar: el registro está relacionado con otros datos.');
        }
    }
}
