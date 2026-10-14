@extends('layouts.app')

@section('title', 'Registro de Pacientes - DIVM')


@section('modulo', 'Módulo Pacientes')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'ingresar', 'icono' => 'bi-file-earmark-person', 'texto' => 'Registrar Paciente', 'activo' => true])
@include('layouts.tab', ['id' => 'consultar', 'icono' => 'bi-list-ul', 'texto' => 'Consultar Historial'])
@include('layouts.tab', ['id' => 'filtros', 'icono' => 'bi-funnel-fill', 'texto' => 'Búsqueda y Filtros'])
@include('layouts.tab', ['id' => 'estatus', 'icono' => 'bi-toggle-on', 'texto' => 'Altas / Bajas (Estatus)'])
@endsection

@section('content')
    <div class="container my-4">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="panel-ingresar" role="tabpanel">

        <div class="card-table mb-4">

            <div class="titulo-panel">

                <h3 class="mb-0">

                    <i class="bi bi-file-earmark-person me-2"></i>

                    Expediente del Paciente

                </h3>

            </div>

            <form action="{{ route('pacientes.store') }}" method="POST">
@csrf

                <!-- DATOS GENERALES -->

                <span class="section-label">
                    Datos Generales
                </span>

                <div class="input-group mb-2">

                    <span class="input-group-text">
                        Nombre:
                    </span>

                    <input name="nombre"
                        type="text"
                        class="form-control"
                        placeholder="Nombre completo"
                    >

                </div>

                <div class="row mb-2">

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text">
                                Fecha Nac.:
                            </span>

                            <input name="fecha_nacimiento"
                                type="date"
                                class="form-control"
                            >

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text">
                                Edad:
                            </span>

                            <input name="edad"
                                type="number"
                                class="form-control"
                                placeholder="Edad"
                            >

                        </div>

                    </div>

                </div>

                <!-- DIRECCIÓN -->

                <span class="section-label mt-3">
                    Dirección Completa
                </span>

                <div class="row g-2 mb-2">

                    <div class="col-md-8">

                        <div class="input-group">

                            <span class="input-group-text">
                                Calle y No.:
                            </span>

                            <input name="calle"
                                type="text"
                                class="form-control"
                                placeholder="Ej. Av. Juárez 123"
                            >

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="input-group">

                            <span
                                class="input-group-text"
                                style="width: 80px;"
                            >
                                C.P.:
                            </span>

                            <input name="cp"
                                type="text"
                                class="form-control"
                                placeholder="00000"
                                maxlength="5"
                            >

                        </div>

                    </div>

                </div>

                <div class="row g-2 mb-2">

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text">
                                Colonia:
                            </span>

                            <input name="colonia"
                                type="text"
                                class="form-control"
                                placeholder="Colonia"
                            >

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text">
                                Municipio:
                            </span>

                            <input name="municipio"
                                type="text"
                                class="form-control"
                                placeholder="Municipio"
                            >

                        </div>

                    </div>

                </div>

                <div class="input-group mb-3">

                    <span class="input-group-text">
                        Referencias:
                    </span>

                    <input name="referencias"
                        type="text"
                        class="form-control"
                        placeholder="Ej. Entre calles, color de casa..."
                    >

                </div>

                <!-- INFORMACIÓN ESCOLAR -->

                <div class="row mb-2">

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text">
                                Escolarizado:
                            </span>

                            <select name="escolarizado" class="form-select">

                                <option value="">
                                    Seleccionar
                                </option>

                                <option value="Sí">Sí</option>

                                <option value="No">No</option>

                            </select>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text">
                                Diagnóstico:
                            </span>

                            <input name="diagnostico"
                                type="text"
                                class="form-control"
                                placeholder="Ej. Autismo, TDAH..."
                            >

                        </div>

                    </div>

                </div>

                <!-- MOTIVO -->

                <div class="input-group mb-2">

                    <span class="input-group-text">
                        Motivo Consulta:
                    </span>

                    <textarea name="motivo_consulta"
                        class="form-control"
                        rows="2"
                        placeholder="Escriba el motivo de la consulta..."
                    ></textarea>

                </div>

                <!-- PADRES -->

                <span class="section-label mt-4">
                    Información de los Padres
                </span>

                <div class="input-group mb-2">

                    <span class="input-group-text">
                        Madre:
                    </span>

                    <input name="madre"
                        type="text"
                        class="form-control"
                        placeholder="Nombre completo"
                    >

                </div>

                <div class="input-group mb-4">

                    <span class="input-group-text">
                        Padre:
                    </span>

                    <input name="padre"
                        type="text"
                        class="form-control"
                        placeholder="Nombre completo"
                    >

                </div>

                <!-- BOTÓN -->

                <button
                    type="submit"
                    class="btn btn-custom w-100 py-2"
                >

                    <i class="bi bi-person-plus me-2"></i>

                    Registrar Paciente

                </button>

            </form>

        </div>

        </div>
        <div class="tab-pane fade" id="panel-consultar" role="tabpanel">

        <div class="card-table mb-4">

            <span class="section-label">

                <i class="bi bi-list-stars me-2"></i>

                Pacientes Registrados

            </span>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Nombre
                            </th>

                            <th>
                                Edad
                            </th>

                            <th>
                                Diagnóstico
                            </th>

                            <th class="text-end">
                                Acciones
                            </th>

                        </tr>

                    </thead>

                    <tbody>
@foreach($pacientes as $p)
<tr>
<td class="fw-bold" style="color: rgb(84, 6, 62);">{{ $p->nombre }}</td>
<td>{{ $p->edad }} años</td>
<td><span class="badge diagnostico">{{ $p->diagnostico }}</span></td>
<td class="text-end">@if(session('rol') === 'Administrador')
<a href="{{ url('/registro/'.$p->id.'/editar') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Editar</a>
<form action="{{ url('/registro/'.$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
@endif</td>
</tr>
@endforeach
</tbody>

                </table>

            </div>

        </div>

        </div>
        <div class="tab-pane fade" id="panel-filtros" role="tabpanel">

        

        <div class="card-table mb-4">

            <span class="section-label">

                <i class="bi bi-search me-2"></i>

                Búsqueda de Pacientes

            </span>

            <div
                class="row g-3 mb-4 p-3 rounded"
                style="
                    background-color: #fcf8fa;
                    border: 1px solid #f0e2ec;
                "
            >

                <div class="col-md-8">

                    <label class="form-label fw-bold text-muted small">

                        Buscar por nombre o tutor:

                    </label>

                    <div class="input-group">

                        <span
                            class="input-group-text"
                            style="width: 45px;"
                        >

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            class="form-control"
                            id="buscarPaciente"
                            placeholder="Escribe el nombre del paciente o tutor..."
                        >

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="form-label fw-bold text-muted small">

                        Filtrar por Diagnóstico:

                    </label>

                    <select class="form-select" id="filtroDx">
                        <option value="">-- Todos los Diagnósticos --</option>
                        @foreach($pacientes->pluck('diagnostico')->filter()->unique()->sort() as $dx)
                            <option value="{{ mb_strtolower($dx) }}">{{ $dx }}</option>
                        @endforeach
                    </select>

                </div>

            </div>

            <!-- TABLA -->

            <div class="table-responsive">

                <table class="table table-hover align-middle" id="tablaPacientes">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Paciente
                            </th>

                            <th>
                                Edad
                            </th>

                            <th>
                                Diagnóstico
                            </th>

                            <th>
                                Madre / Padre
                            </th>

                            <th class="text-end">
                                Acción
                            </th>

                        </tr>

                    </thead>

                    <tbody>
@foreach($pacientes as $p)
<tr data-texto="{{ mb_strtolower($p->nombre.' '.$p->madre.' '.$p->padre) }}" data-dx="{{ mb_strtolower($p->diagnostico ?? '') }}">
<td class="fw-bold" style="color: rgb(84, 6, 62);">{{ $p->nombre }}</td>
<td>{{ $p->edad }} años</td>
<td><span class="badge diagnostico">{{ $p->diagnostico }}</span></td>
<td class="text-muted small"><i class="bi bi-person-heart"></i> {{ $p->madre }}<br><i class="bi bi-person"></i> {{ $p->padre }}</td>
<td class="text-end">@if(session('rol') === 'Administrador')<a href="{{ url('/registro/'.$p->id.'/editar') }}" class="btn btn-sm btn-expediente"><i class="bi bi-folder2-open me-1"></i>Abrir Expediente</a>@endif</td>
</tr>
@endforeach
</tbody>

                </table>
            </div>
        </div>

        </div>
        <div class="tab-pane fade" id="panel-estatus" role="tabpanel">

        <div class="card-table mb-4">

            <span class="section-label">
                <i class="bi bi-toggle-on me-2"></i>
                Altas / Bajas (Estatus)
            </span>

            <p class="text-muted small">Da de baja a un paciente que ya no asiste o vuelve a darlo de alta. No se borra su expediente.</p>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Nombre</th><th>Diagnóstico</th><th class="text-center">Estatus</th><th class="text-end">Acción</th></tr>
                    </thead>
                    <tbody>
                    @foreach($pacientes as $p)
                        @php $activo = ($p->estatus ?? 'Activo') === 'Activo'; @endphp
                        <tr>
                            <td class="fw-bold" style="color: rgb(84, 6, 62);">{{ $p->nombre }}</td>
                            <td><span class="badge diagnostico">{{ $p->diagnostico }}</span></td>
                            <td class="text-center"><span class="badge {{ $activo ? 'estado-activo' : 'estado-inactivo' }}">{{ $activo ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-end">
                                <form action="{{ url('/registro/'.$p->id.'/estatus') }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm {{ $activo ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                        <i class="bi {{ $activo ? 'bi-person-dash' : 'bi-person-check' }}"></i> {{ $activo ? 'Dar de baja' : 'Dar de alta' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var q = document.getElementById('buscarPaciente'), dx = document.getElementById('filtroDx');
    function filtrar() {
        var t = (q.value || '').toLowerCase().trim(), d = dx.value;
        document.querySelectorAll('#tablaPacientes tbody tr').forEach(function (tr) {
            var ok = (!t || tr.dataset.texto.indexOf(t) > -1) && (!d || tr.dataset.dx === d);
            tr.style.display = ok ? '' : 'none';
        });
    }
    q.addEventListener('input', filtrar);
    dx.addEventListener('change', filtrar);
})();
</script>
@endpush
