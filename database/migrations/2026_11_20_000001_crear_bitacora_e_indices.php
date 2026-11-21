<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Crea la bitácora y agrega índices para que el sistema siga rápido con muchos pacientes. Es seguro correrla varias veces. */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('bitacora')) {
            Schema::create('bitacora', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('id_usuario')->nullable();
                $t->string('usuario', 150)->nullable();
                $t->string('accion', 10);
                $t->string('ruta', 190);
                $t->string('ip', 45)->nullable();
                $t->timestamp('fecha')->useCurrent();
                $t->index('fecha');
            });
        }

        foreach ([['pacientes', 'nombre'], ['planes', 'paciente'], ['recomendaciones', 'paciente'],
                  ['informes', 'paciente'], ['horarios_atencion', 'paciente']] as [$tabla, $col]) {
            if (Schema::hasTable($tabla) && Schema::hasColumn($tabla, $col)) {
                try {
                    Schema::table($tabla, fn (Blueprint $t) => $t->index($col, "idx_{$tabla}_{$col}"));
                } catch (\Throwable $e) {
                    // el índice ya existe
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};
