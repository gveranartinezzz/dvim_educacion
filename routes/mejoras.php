<?php

use App\Http\Controllers\MejorasController;
use Illuminate\Support\Facades\Route;

// Cerrar sesión con POST (un enlace de otra página ya no puede cerrarte la sesión)
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout.post');

Route::middleware(function ($request, $next) {
    if (!session()->has('id_usuario')) {
        return redirect()->route('login');
    }
    return $next($request);
})->group(function () {
    Route::get('/panel', [MejorasController::class, 'panel'])->name('panel');
    Route::get('/expediente/{nombre?}', [MejorasController::class, 'expediente'])->where('nombre', '.*')->name('expediente');
    Route::get('/exportar/pacientes', [MejorasController::class, 'exportarPacientes'])->name('exportar.pacientes');

    Route::get('/bitacora', [MejorasController::class, 'bitacora'])->middleware(function ($request, $next) {
        if (session('rol') !== 'Administrador') {
            abort(403, 'Solo administradores.');
        }
        return $next($request);
    })->name('bitacora');
});
