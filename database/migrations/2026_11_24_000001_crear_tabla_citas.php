<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('citas')) return;
        Schema::create('citas', function (Blueprint $t) {
            $t->id();
            $t->string('paciente', 150);
            $t->unsignedBigInteger('id_usuario')->nullable();
            $t->date('fecha');
            $t->time('hora_inicio');
            $t->time('hora_fin');
            $t->string('motivo', 255);
            $t->text('notas')->nullable();
            $t->string('estatus', 20)->default('Programada');
            $t->timestamps();
            $t->index(['fecha', 'hora_inicio']);
        });
    }

    public function down(): void { Schema::dropIfExists('citas'); }
};
