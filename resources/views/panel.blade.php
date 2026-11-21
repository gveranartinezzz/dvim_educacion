@extends('layouts.app')

@section('title', 'Panel - DIVM')
@section('modulo', 'Panel general')

@section('content')
<div class="container-fluid px-0">

    <div class="card-table mb-4">
        <span class="section-label"><i class="bi bi-speedometer2 me-2"></i>Resumen del centro</span>

        <div class="indicadores mb-3">
            @foreach($totales as $etiqueta => $valor)
                <div class="indicador">
                    <div class="indicador-valor">{{ $valor }}</div>
                    <div class="indicador-etiqueta">{{ $etiqueta }}</div>
                </div>
            @endforeach
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/exportar/pacientes') }}" class="btn btn-divm"><i class="bi bi-file-earmark-spreadsheet-fill me-1"></i>Descargar pacientes (Excel)</a>
            <a href="{{ url('/expediente') }}" class="btn btn-divm-outline"><i class="bi bi-folder2-open me-1"></i>Ver expediente de un paciente</a>
            @if($esAdmin)
                <a href="{{ url('/bitacora') }}" class="btn btn-divm-outline"><i class="bi bi-clock-history me-1"></i>Bitácora de movimientos</a>
            @endif
        </div>
    </div>

    @if($esAdmin && $pendientes > 0)
        <div class="alert alert-warning">
            <i class="bi bi-person-exclamation me-1"></i>
            Hay <strong>{{ $pendientes }}</strong> cuenta(s) esperando activación.
            <a class="alert-link" href="{{ url('/usuarios') }}">Revisar en Usuarios</a>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card-table h-100">
                <span class="section-label"><i class="bi bi-bar-chart-fill me-2"></i>Pacientes por diagnóstico</span>
                @php $max = max(1, (int) $porDiagnostico->max()); @endphp
                @forelse($porDiagnostico as $nombre => $n)
                    <div class="barra-fila">
                        <div class="barra-texto"><span>{{ $nombre }}</span><strong>{{ $n }}</strong></div>
                        <div class="barra"><span style="width: {{ round($n / $max * 100) }}%"></span></div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aún no hay pacientes registrados.</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-table h-100">
                <span class="section-label"><i class="bi bi-people me-2"></i>Pacientes por edad</span>
                @php $maxE = max(1, max($porEdad)); @endphp
                @foreach($porEdad as $rango => $n)
                    <div class="barra-fila">
                        <div class="barra-texto"><span>{{ $rango }} años</span><strong>{{ $n }}</strong></div>
                        <div class="barra"><span style="width: {{ round($n / $maxE * 100) }}%"></span></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <div class="card-table">
                <span class="section-label"><i class="bi bi-exclamation-circle me-2"></i>Pacientes sin plan de trabajo</span>
                @if(count($sinPlan))
                    <ul class="mb-0">
                        @foreach($sinPlan as $n)
                            <li><a href="{{ url('/expediente/' . rawurlencode($n)) }}">{{ $n }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">Todos los pacientes activos tienen plan. 👏</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
