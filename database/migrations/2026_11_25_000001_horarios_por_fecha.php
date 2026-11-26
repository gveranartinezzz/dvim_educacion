<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Los horarios ahora tienen fecha exacta; los anteriores (sin fecha) se siguen repitiendo cada semana. */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('horarios_atencion', function (Blueprint $t) {
            if (!Schema::hasColumn('horarios_atencion', 'fecha'))  $t->date('fecha')->nullable();
            if (!Schema::hasColumn('horarios_atencion', 'repite')) $t->boolean('repite')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('horarios_atencion', function (Blueprint $t) {
            if (Schema::hasColumn('horarios_atencion', 'repite')) $t->dropColumn('repite');
            if (Schema::hasColumn('horarios_atencion', 'fecha'))  $t->dropColumn('fecha');
        });
    }
};
