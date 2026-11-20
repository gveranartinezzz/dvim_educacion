<?php

namespace App\Providers;

use App\Models\Paciente;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // El paciente se escribe UNA sola vez (en Pacientes). Los demás formularios lo eligen de esta lista.
        View::composer(
            ['plan', 'recomendaciones', 'informe', 'distribucion', 'editar_plan', 'editar_recomendacion', 'editar_informe'],
            function ($view) {
                try {
                    $lista = Paciente::query()->orderBy('nombre')->get();
                } catch (\Throwable $e) {
                    $lista = collect();
                }
                $view->with('listaPacientes', $lista);
            }
        );
    }
}
