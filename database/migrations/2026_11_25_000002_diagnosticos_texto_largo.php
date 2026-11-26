<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** El diagnóstico cifrado ocupa más espacio que el texto original: las columnas pasan a TEXT. */
return new class extends Migration {
    public function up(): void
    {
        foreach ([['pacientes', 'diagnostico'], ['informes', 'hc_diagnostico'], ['informes_valoracion', 'hc_diagnostico']] as [$t, $c]) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, $c)) {
                Schema::table($t, fn (Blueprint $b) => $b->text($c)->nullable()->change());
            }
        }
    }

    public function down(): void {}
};
