<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\RecomendacionController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\ValoracionController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\VerificacionController;

// ---------- Login (público) ----------
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'form'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post'); // máx. 10 intentos por minuto
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Verificación en dos pasos (código por correo)
Route::get('/verificar', [VerificacionController::class, 'form'])->name('verificar');
Route::post('/verificar', [VerificacionController::class, 'verificar'])->middleware('throttle:10,1')->name('verificar.post');
Route::post('/verificar/reenviar', [VerificacionController::class, 'reenviar'])->middleware('throttle:5,1')->name('verificar.reenviar');

$soloSesion = function ($request, $next) {
    if (!session()->has('id_usuario')) {
        return redirect()->route('login');
    }
    return $next($request);
};

$soloAdmin = function ($request, $next) {
    if (session('rol') !== 'Administrador') {
        abort(403, 'Solo administradores.');
    }
    return $next($request);
};

Route::group(["middleware" => [$soloSesion]], function () use ($soloAdmin) {

    Route::view('/inicio', 'informacion');

    // Pacientes
    Route::get('/registro', [PacienteController::class, 'index']);
    Route::post('/registro', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::put('/registro/{id}/estatus', [PacienteController::class, 'estatus']);

    // Planes
    Route::get('/plan', [PlanController::class, 'index']);
    Route::post('/plan', [PlanController::class, 'store'])->name('planes.store');

    // Recomendaciones
    Route::get('/recomendaciones', [RecomendacionController::class, 'index']);
    Route::post('/recomendaciones', [RecomendacionController::class, 'store'])->name('recomendaciones.store');

    // Informes
    Route::get('/informe', [InformeController::class, 'index']);
    Route::get('/informe/grafica', [InformeController::class, 'grafica']);
    Route::post('/informe', [InformeController::class, 'store'])->name('informes.store');

    // Usuarios (el controlador valida que sea administrador)
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

    // Avances
    Route::get('/avances', [AvanceController::class, 'index']);
    Route::post('/avances', [AvanceController::class, 'store'])->name('avances.store');
    Route::put('/avances/{id}', [AvanceController::class, 'update']);

    // Valoración
    Route::get('/valoracion', [ValoracionController::class, 'index']);
    Route::post('/valoracion', [ValoracionController::class, 'store'])->name('valoracion.store');

    // Distribución de horarios
    Route::get('/distribucion', [HorarioController::class, 'index']);
    Route::post('/distribucion', [HorarioController::class, 'store'])->name('horarios.store');
    Route::delete('/distribucion/{id}', [HorarioController::class, 'destroy']);

    // Asistente de chat
    Route::post('/chatbot', [ChatbotController::class, 'responder'])->middleware('throttle:20,1');

    // PDF (se abren en otra pestaña)
    Route::get('/pdf/informe/{id}', [PdfController::class, 'informe']);
    Route::get('/pdf/valoracion/{id}', [PdfController::class, 'valoracion']);
    Route::get('/pdf/avance/{id}', [PdfController::class, 'avance']);
    Route::get('/pdf/plan/{id}', [PdfController::class, 'plan']);
    Route::get('/pdf/recomendacion/{id}', [PdfController::class, 'recomendacion']);

    // ---------- Editar / eliminar: solo Administrador ----------
    Route::group(["middleware" => [$soloAdmin]], function () {
        Route::get('/registro/{id}/editar', [PacienteController::class, 'edit']);
        Route::put('/registro/{id}', [PacienteController::class, 'update']);
        Route::delete('/registro/{id}', [PacienteController::class, 'destroy']);

        Route::get('/plan/{id}/editar', [PlanController::class, 'edit']);
        Route::put('/plan/{id}', [PlanController::class, 'update']);
        Route::delete('/plan/{id}', [PlanController::class, 'destroy']);

        Route::get('/recomendaciones/{id}/editar', [RecomendacionController::class, 'edit']);
        Route::put('/recomendaciones/{id}', [RecomendacionController::class, 'update']);
        Route::delete('/recomendaciones/{id}', [RecomendacionController::class, 'destroy']);

        Route::get('/informe/{id}/editar', [InformeController::class, 'edit']);
        Route::put('/informe/{id}', [InformeController::class, 'update']);
        Route::delete('/informe/{id}', [InformeController::class, 'destroy']);

        Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit']);
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
    });
});
