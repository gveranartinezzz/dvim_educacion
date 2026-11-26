<?php

use App\Http\Controllers\HorarioController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\MejorasController;
use App\Http\Middleware\SoloAdmin;
use App\Http\Middleware\SoloSesion;
use Illuminate\Support\Facades\Route;

// Cerrar sesión con POST (un enlace de otra página ya no puede cerrarte la sesión)
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout.post');

// El control de acceso usa clases (no funciones) para que Laravel pueda guardar las rutas en caché
Route::group(['middleware' => [SoloSesion::class]], function () {
    Route::get('/panel', [MejorasController::class, 'panel'])->name('panel');
    Route::get('/expediente/{nombre?}', [MejorasController::class, 'expediente'])->where('nombre', '.*')->name('expediente');
    Route::get('/exportar/pacientes', [MejorasController::class, 'exportarPacientes'])->name('exportar.pacientes');

    // Inicio editable (solo el administrador guarda cambios)
    Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');

    // Distribución: editar un horario ya asignado
    Route::put('/distribucion/{id}', [HorarioController::class, 'update'])->whereNumber('id')->name('horarios.update');

    Route::group(['middleware' => [SoloAdmin::class]], function () {
        Route::post('/inicio', [InicioController::class, 'guardar'])->name('inicio.guardar');
        Route::get('/bitacora', [MejorasController::class, 'bitacora'])->name('bitacora');
    });
});
