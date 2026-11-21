@extends('layouts.app')

@section('title', 'Bitácora - DIVM')
@section('modulo', 'Bitácora')

@section('content')
<div class="card-table">
    <span class="section-label"><i class="bi bi-clock-history me-2"></i>Últimos movimientos</span>

    @if(!$existe)
        <div class="alert alert-warning mb-0">Falta crear la bitácora. En la terminal corre: <code>php artisan migrate</code></div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Dónde</th><th>IP</th></tr></thead>
                <tbody>
                @forelse($registros as $r)
                    <tr>
                        <td>{{ $r->fecha }}</td>
                        <td>{{ $r->usuario }}</td>
                        <td>{{ ['POST' => 'Guardó', 'PUT' => 'Editó', 'PATCH' => 'Editó', 'DELETE' => 'Eliminó'][$r->accion] ?? $r->accion }}</td>
                        <td><code>{{ $r->ruta }}</code></td>
                        <td>{{ $r->ip }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Todavía no hay movimientos.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
