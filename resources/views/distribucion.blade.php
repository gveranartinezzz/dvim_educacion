@extends('layouts.app')

@section('title', 'Gestión de Horarios y Espacios - DIVM')



@section('modulo', 'Módulo Distribución')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'enviar', 'icono' => 'bi-calendar-plus', 'texto' => 'Asignar Horario', 'activo' => true])
@include('layouts.tab', ['id' => 'banco', 'icono' => 'bi-clock-history', 'texto' => 'Historial'])
@include('layouts.tab', ['id' => 'distribucion', 'icono' => 'bi-grid-3x3-gap-fill', 'texto' => 'Distribución'])
@endsection

@section('content')
@php
    $slug = fn($d) => strtolower(strtr($d, ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u']));
    $hora = fn($t) => \Carbon\Carbon::parse($t)->format('h:i a');
    $idx  = $terapeutas->pluck('id_usuario')->values()->flip();
    $jsH  = $horarios->map(fn($h) => [
                'id' => $h->id_horario, 'id_usuario' => $h->id_usuario, 'terapeuta' => $h->terapeuta,
                'dia_semana' => $h->dia_semana,
                'hora_inicio' => substr($h->hora_inicio, 0, 5), 'hora_fin' => substr($h->hora_fin, 0, 5),
            ])->values();
@endphp


<div class="tab-content" id="v-pills-tabContent">

<!-- ================= ASIGNAR HORARIO ================= -->
<div class="tab-pane fade show active" id="panel-enviar">
<div class="container-fluid"><div class="row justify-content-center">
<div class="col-md-10 bg-white p-4 rounded shadow-sm">

<div class="titulo-banner">
    <h3 class="mb-0"><i class="bi bi-calendar-plus me-2"></i> Asignar Horario</h3>
</div>

<div class="conflict-alert mb-3" id="alertaConflicto">
    <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
    <strong>Posible conflicto detectado:</strong> <span id="textoConflicto"></span>
</div>

<form action="{{ route('horarios.store') }}" method="POST" id="formHorario">
@csrf
<span class="section-label">Información</span>

<div class="input-group mb-3">
    <span class="input-group-text"><i class="bi bi-person me-1"></i> Paciente</span>
    <input type="text" name="paciente" class="form-control" placeholder="Nombre del niño/a" value="{{ old('paciente') }}" required>
</div>

<div class="input-group mb-4">
    <span class="input-group-text"><i class="bi bi-person-badge me-1"></i> Terapeuta</span>
    <select name="id_usuario" class="form-select" id="selectTerapeuta" required>
        <option value="" disabled selected>Seleccionar terapeuta</option>
        @foreach($terapeutas as $t)
            <option value="{{ $t->id_usuario }}" {{ old('id_usuario') == $t->id_usuario ? 'selected' : '' }}>{{ $t->nombre }} {{ $t->apaterno }}</option>
        @endforeach
    </select>
</div>

<span class="section-label">Horario</span>

<div class="row mb-4">
    <div class="col-md-4 mb-2">
        <label class="form-label fw-bold text-secondary small">Día de la semana</label>
        <select name="dia_semana" class="form-select" id="selectDia" required>
            <option value="" disabled selected>Seleccionar día</option>
            @foreach($dias as $d)<option {{ old('dia_semana') == $d ? 'selected' : '' }}>{{ $d }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label fw-bold text-secondary small">Hora de inicio</label>
        <input type="time" name="hora_inicio" id="horaInicio" class="form-control" value="{{ old('hora_inicio') }}" required>
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label fw-bold text-secondary small">Hora de fin</label>
        <input type="time" name="hora_fin" id="horaFin" class="form-control" value="{{ old('hora_fin') }}" required>
    </div>
</div>

<button type="submit" class="btn btn-custom w-100 py-3 fs-6"><i class="bi bi-check-circle me-2"></i> GUARDAR HORARIO</button>
</form>

</div></div></div>
</div>

<!-- ================= HISTORIAL ================= -->
<div class="tab-pane fade" id="panel-banco">
<div class="container-fluid"><div class="row justify-content-center">

<div class="col-md-11 card-table mb-3">
    <span class="section-label mt-0"><i class="bi bi-funnel me-2"></i> Filtrar Historial</span>
    <form class="row g-3" id="formFiltros" onsubmit="return false">
        <div class="col-md-4">
            <label class="form-label fw-bold text-secondary small">Paciente</label>
            <input type="text" id="fPaciente" class="form-control" placeholder="Buscar paciente...">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold text-secondary small">Terapeuta</label>
            <input type="text" id="fTerapeuta" class="form-control" placeholder="Buscar terapeuta...">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold text-secondary small">Día</label>
            <select id="fDia" class="form-select">
                <option value="">Todos los días</option>
                @foreach($dias as $d)<option>{{ $d }}</option>@endforeach
            </select>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="button" class="btn btn-custom px-4" onclick="filtrarHorarios()"><i class="bi bi-search me-1"></i> Filtrar</button>
            <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()"><i class="bi bi-arrow-counterclockwise"></i></button>
        </div>
    </form>
</div>

<div class="col-md-11 card-table">
<div class="table-responsive">
<table class="table table-hover align-middle mb-0">
    <thead style="background:var(--primary); color:#fff;">
        <tr><th>Paciente</th><th>Terapeuta</th><th>Día</th><th>Horario</th><th class="text-center">Acciones</th></tr>
    </thead>
    <tbody id="tablaHorarios">
    @foreach($horarios as $h)
        <tr data-paciente="{{ $h->paciente }}" data-terapeuta="{{ $h->terapeuta }}" data-dia="{{ $h->dia_semana }}">
            <td class="fw-bold">{{ $h->paciente }}</td>
            <td><i class="bi bi-person-circle me-1 text-secondary"></i> {{ $h->terapeuta }}</td>
            <td><span class="badge badge-{{ $slug($h->dia_semana) }} text-white">{{ $h->dia_semana }}</span></td>
            <td><i class="bi bi-clock me-1 text-muted"></i> {{ $hora($h->hora_inicio) }} — {{ $hora($h->hora_fin) }}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-eliminar" type="button" onclick="confirmarEliminar('{{ $h->id_horario }}', @js($h->paciente))"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
</div>

</div></div>
</div>

<!-- ================= DISTRIBUCIÓN ================= -->
<div class="tab-pane fade" id="panel-distribucion">
<div class="container-fluid"><div class="row justify-content-center">
<div class="col-md-11 bg-white p-4 rounded shadow-sm">

<span class="section-label"><i class="bi bi-grid-3x3-gap-fill me-2"></i> Distribución de Horarios</span>
<p class="text-muted small mb-4">Resumen de carga semanal del centro.</p>

<div class="row g-2 mb-4">
@foreach($dias as $d)
    <div class="col-md-2 col-4">
        <div class="day-card">
            <h6>{{ $d }}</h6>
            <h3>{{ $horarios->where('dia_semana', $d)->count() }}</h3>
            <small class="text-muted">horarios</small>
        </div>
    </div>
@endforeach
</div>

<span class="section-label"><i class="bi bi-calendar-week me-2"></i> Calendario Semanal</span>

<div class="d-flex flex-wrap gap-2 mb-3">
@foreach($terapeutas as $i => $t)
    <span class="badge tc-{{ $i % 8 }} px-3 py-2"><i class="bi bi-person-fill me-1"></i> {{ $t->nombre }} {{ $t->apaterno }}</span>
@endforeach
</div>

<div style="overflow-x:auto;">
<div class="calendar-grid" style="min-width:700px;">
    <div class="cal-header" style="background:transparent;"></div>
    @foreach($dias as $d)<div class="cal-header">{{ $d }}</div>@endforeach

    @for($hr = 7; $hr <= 19; $hr++)
        <div class="cal-time">{{ $hr < 12 ? $hr.' am' : ($hr == 12 ? '12 pm' : ($hr - 12).' pm') }}</div>
        @foreach($dias as $d)
            <div class="cal-cell">
            @foreach($horarios->where('dia_semana', $d) as $h)
                @if((int) substr($h->hora_inicio, 0, 2) === $hr)
                    <div class="cal-event tc-{{ ($idx[$h->id_usuario] ?? 0) % 8 }}" title="{{ $h->paciente }} | {{ $h->terapeuta }} | {{ substr($h->hora_inicio,0,5) }}-{{ substr($h->hora_fin,0,5) }}">
                        <div>{{ $h->paciente }}</div>
                        <div style="opacity:.85;font-weight:400">{{ substr($h->hora_inicio,0,5) }}-{{ substr($h->hora_fin,0,5) }}</div>
                    </div>
                @endif
            @endforeach
            </div>
        @endforeach
    @endfor
</div>
</div>

<span class="section-label mt-4"><i class="bi bi-table me-2"></i> Detalle Completo</span>
<div class="table-responsive">
<table class="table table-hover align-middle">
    <thead style="background:var(--primary);color:#fff;"><tr><th>Día</th><th>Paciente</th><th>Terapeuta</th><th>Horario</th></tr></thead>
    <tbody>
    @foreach($horarios->sortBy(fn($h) => array_search($h->dia_semana, $dias)) as $h)
        <tr>
            <td><span class="badge badge-{{ $slug($h->dia_semana) }} text-white">{{ $h->dia_semana }}</span></td>
            <td>{{ $h->paciente }}</td>
            <td>{{ $h->terapeuta }}</td>
            <td>{{ $hora($h->hora_inicio) }} — {{ $hora($h->hora_fin) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

</div></div></div>
</div>

</div>

<!-- ================= MODAL ELIMINAR ================= -->
<form id="formEliminar" method="POST">@csrf @method('DELETE')</form>

<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header text-white" style="background:var(--primary);">
        <h5 class="modal-title fw-bold"><i class="bi bi-trash me-2"></i> Eliminar Horario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body text-center py-4">
        <i class="bi bi-exclamation-circle fs-1 text-danger d-block mb-3"></i>
        <p class="fs-6">¿Estás seguro de eliminar el horario de <strong id="nombreEliminar"></strong>?</p>
        <p class="text-muted small">Esta acción no se puede deshacer.</p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger fw-bold" onclick="eliminarHorario()"><i class="bi bi-trash me-1"></i> Sí, eliminar</button>
    </div>
</div></div>
</div>
@endsection

@push('scripts')
<script>
const horariosExistentes = @json($jsH);

function verificarConflicto() {
    const idTerapeuta = document.getElementById('selectTerapeuta').value;
    const dia    = document.getElementById('selectDia').value;
    const inicio = document.getElementById('horaInicio').value;
    const fin    = document.getElementById('horaFin').value;
    if (!idTerapeuta || !dia || !inicio || !fin) return;

    const toMin = t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
    const nI = toMin(inicio), nF = toMin(fin);
    const alerta = document.getElementById('alertaConflicto');
    const texto  = document.getElementById('textoConflicto');

    if (nF <= nI) {
        texto.textContent = "La hora de fin debe ser posterior a la hora de inicio.";
        alerta.style.display = 'block';
        return;
    }

    const conflicto = horariosExistentes.find(h =>
        String(h.id_usuario) === String(idTerapeuta) && h.dia_semana === dia &&
        nI < toMin(h.hora_fin) && nF > toMin(h.hora_inicio));

    if (conflicto) {
        texto.textContent = `${conflicto.terapeuta} ya tiene un horario el ${conflicto.dia_semana} de ${conflicto.hora_inicio} a ${conflicto.hora_fin}.`;
        alerta.style.display = 'block';
    } else {
        alerta.style.display = 'none';
    }
}

['selectTerapeuta', 'selectDia', 'horaInicio', 'horaFin'].forEach(id =>
    document.getElementById(id)?.addEventListener('change', verificarConflicto));

function filtrarHorarios() {
    const paciente  = document.getElementById('fPaciente').value.toLowerCase();
    const terapeuta = document.getElementById('fTerapeuta').value.toLowerCase();
    const dia       = document.getElementById('fDia').value;

    document.querySelectorAll('#tablaHorarios tr').forEach(fila => {
        const p = (fila.dataset.paciente || '').toLowerCase();
        const t = (fila.dataset.terapeuta || '').toLowerCase();
        const d = fila.dataset.dia || '';
        fila.style.display = (!paciente || p.includes(paciente)) && (!terapeuta || t.includes(terapeuta)) && (!dia || d === dia) ? '' : 'none';
    });
}

function limpiarFiltros() {
    document.getElementById('fPaciente').value = '';
    document.getElementById('fTerapeuta').value = '';
    document.getElementById('fDia').value = '';
    filtrarHorarios();
}

let idEliminar = null;

function confirmarEliminar(id, nombre) {
    idEliminar = id;
    document.getElementById('nombreEliminar').textContent = nombre;
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}

function eliminarHorario() {
    if (!idEliminar) return;
    const f = document.getElementById('formEliminar');
    f.action = "{{ url('/distribucion') }}/" + idEliminar;
    f.submit();
}
</script>
@endpush
