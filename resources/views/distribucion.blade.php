@extends('layouts.app')

@section('title', 'Gestión de Horarios y Espacios - DIVM')



@section('modulo', 'Módulo Distribución')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'enviar', 'icono' => 'bi-calendar-plus', 'texto' => 'Asignar Horario', 'activo' => true])
@include('layouts.tab', ['id' => 'banco', 'icono' => 'bi-clock-history', 'texto' => 'Historial'])
@include('layouts.tab', ['id' => 'distribucion', 'icono' => 'bi-grid-3x3-gap-fill', 'texto' => 'Distribución'])
@include('layouts.tab', ['id' => 'guias', 'icono' => 'bi-journal-bookmark-fill', 'texto' => 'Guías e Indicadores'])
@endsection

@section('content')
@php
    $slug = fn($d) => strtolower(strtr($d, ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u']));
    $hora = fn($t) => \Carbon\Carbon::parse($t)->format('h:i a');
    $idx  = $terapeutas->pluck('id_usuario')->values()->flip();
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $ref   = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) request('semana')) ? \Carbon\Carbon::parse(request('semana')) : now();
    $lunes = $ref->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->startOfDay();
    $fechaDia = [];                                   // 'Lunes' => Carbon de esa fecha en la semana mostrada
    foreach ($dias as $i => $nomDia) { $fechaDia[$nomDia] = $lunes->copy()->addDays($i); }
    $ultimo = $lunes->copy()->addDays(count($dias) - 1);
    $fmtLargo = fn ($f) => $f->day . ' de ' . $meses[$f->month - 1];
    $esSemanaActual = $lunes->isSameDay(now()->startOfWeek(\Carbon\Carbon::MONDAY));
    $proxima = function ($nomDia) use ($dias) {       // próxima fecha en que cae ese día (desde hoy)
        $n = array_search($nomDia, $dias) + 1;        // lunes = 1
        $f = now()->startOfDay();
        while ($f->dayOfWeekIso !== $n) { $f->addDay(); }
        return $f;
    };
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
    <p class="mb-0 small">Define qué terapeuta atiende a cada paciente, qué día y en qué horario (lunes a sábado, de 07:00 a 20:00)</p>
</div>

<div class="conflict-alert mb-3" id="alertaConflicto">
    <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
    <strong>Posible conflicto detectado:</strong> <span id="textoConflicto"></span>
</div>

<form action="{{ route('horarios.store') }}" method="POST" id="formHorario">
@csrf
<span class="section-label"><i class="bi bi-person-lines-fill me-2"></i>Paciente y terapeuta</span>

@include('layouts.paciente_select')

<div class="input-group mb-4">
    <span class="input-group-text"><i class="bi bi-person-badge me-1"></i> Terapeuta</span>
    <select name="id_usuario" class="form-select" id="selectTerapeuta" required>
        <option value="" disabled selected>Seleccionar terapeuta</option>
        @foreach($terapeutas as $t)
            <option value="{{ $t->id_usuario }}" {{ old('id_usuario') == $t->id_usuario ? 'selected' : '' }}>{{ $t->nombre }} {{ $t->apaterno }}</option>
        @endforeach
    </select>
</div>

<span class="section-label"><i class="bi bi-clock me-2"></i>Día y horas</span>

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
        <input type="time" name="hora_inicio" id="horaInicio" class="form-control" min="07:00" max="19:59" value="{{ old('hora_inicio') }}" required>
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label fw-bold text-secondary small">Hora de fin</label>
        <input type="time" name="hora_fin" id="horaFin" class="form-control" min="07:01" max="20:00" value="{{ old('hora_fin') }}" required>
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
        <tr><th>Paciente</th><th>Terapeuta</th><th>Día</th><th>Próxima fecha</th><th>Horas</th><th class="text-center">Acciones</th></tr>
    </thead>
    <tbody id="tablaHorarios">
    @foreach($horarios as $h)
        <tr data-paciente="{{ $h->paciente }}" data-terapeuta="{{ $h->terapeuta }}" data-dia="{{ $h->dia_semana }}"
            data-id="{{ $h->id_horario }}" data-usuario="{{ $h->id_usuario }}" data-inicio="{{ substr($h->hora_inicio, 0, 5) }}" data-fin="{{ substr($h->hora_fin, 0, 5) }}">
            <td class="fw-bold">{{ $h->paciente }}</td>
            <td><i class="bi bi-person-circle me-1 text-secondary"></i> {{ $h->terapeuta }}</td>
            <td><span class="badge badge-{{ $slug($h->dia_semana) }} text-white">{{ $h->dia_semana }}</span></td>
            <td>{{ $proxima($h->dia_semana)->format('d/m/Y') }}</td>
            <td><i class="bi bi-clock me-1 text-muted"></i> {{ substr($h->hora_inicio, 0, 5) }} – {{ substr($h->hora_fin, 0, 5) }} h</td>
            <td class="text-center text-nowrap">
                <button class="btn btn-sm btn-outline-primary" type="button" onclick="editarHorario(this.closest('tr'))" title="Editar horario"><i class="bi bi-pencil"></i></button>
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

<div class="d-flex flex-wrap gap-2 mb-2">
@foreach($terapeutas as $i => $t)
    <button type="button" class="badge tc-{{ $i % 8 }} px-3 py-2 border-0" data-filtro-terapeuta="{{ $t->id_usuario }}" title="Ver solo este terapeuta"><i class="bi bi-person-fill me-1"></i> {{ $t->nombre }} {{ $t->apaterno }}</button>
@endforeach
<button type="button" class="badge bg-secondary px-3 py-2 border-0" data-filtro-terapeuta="" title="Ver a todos"><i class="bi bi-people-fill me-1"></i> Todos</button>
</div>
<p class="form-text mb-3"><i class="bi bi-info-circle me-1"></i>Toca el nombre de un terapeuta para ver solo sus horarios.</p>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
    <a class="btn btn-sm btn-outline-secondary" href="{{ url('/distribucion?semana=' . $lunes->copy()->subWeek()->toDateString()) }}"><i class="bi bi-chevron-left"></i> Semana anterior</a>
    <div class="text-center fw-bold text-divm">
        Semana del {{ $fmtLargo($lunes) }} al {{ $fmtLargo($ultimo) }} de {{ $ultimo->year }}
        @if($esSemanaActual)<span class="badge bg-success ms-1">Esta semana</span>@endif
    </div>
    <div>
        @unless($esSemanaActual)<a class="btn btn-sm btn-outline-primary" href="{{ url('/distribucion') }}">Hoy</a>@endunless
        <a class="btn btn-sm btn-outline-secondary" href="{{ url('/distribucion?semana=' . $lunes->copy()->addWeek()->toDateString()) }}">Semana siguiente <i class="bi bi-chevron-right"></i></a>
    </div>
</div>

<div style="overflow-x:auto;">
<div class="calendar-grid" style="min-width:700px;">
    <div class="cal-header" style="background:transparent;"><small>{{ $ultimo->year }}</small></div>
    @foreach($dias as $d)
        <div class="cal-header" @if($fechaDia[$d]->isToday()) style="outline:3px solid #ffc107;outline-offset:-3px;" @endif>
            {{ $d }}<br><small style="font-weight:400">{{ $fechaDia[$d]->format('d/m/Y') }}</small>
        </div>
    @endforeach

    @for($hr = 7; $hr <= 19; $hr++)
        <div class="cal-time">{{ sprintf('%02d:00', $hr) }}</div>
        @foreach($dias as $d)
            <div class="cal-cell">
            @foreach($horarios->where('dia_semana', $d) as $h)
                @if((int) substr($h->hora_inicio, 0, 2) === $hr)
                    <div class="cal-event tc-{{ ($idx[$h->id_usuario] ?? 0) % 8 }}" data-terapeuta-id="{{ $h->id_usuario }}" title="{{ $h->paciente }} | {{ $h->terapeuta }} | {{ substr($h->hora_inicio,0,5) }}-{{ substr($h->hora_fin,0,5) }}">
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
    <thead style="background:var(--primary);color:#fff;"><tr><th>Día</th><th>Fecha</th><th>Paciente</th><th>Terapeuta</th><th>Horas</th></tr></thead>
    <tbody>
    @foreach($horarios->sortBy(fn($h) => array_search($h->dia_semana, $dias)) as $h)
        <tr>
            <td><span class="badge badge-{{ $slug($h->dia_semana) }} text-white">{{ $h->dia_semana }}</span></td>
            <td>{{ $fechaDia[$h->dia_semana]->format('d/m/Y') }}</td>
            <td>{{ $h->paciente }}</td>
            <td>{{ $h->terapeuta }}</td>
            <td>{{ substr($h->hora_inicio, 0, 5) }} – {{ substr($h->hora_fin, 0, 5) }} h</td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

</div></div></div>

</div>
        <div class="tab-pane fade" id="panel-guias" role="tabpanel">
@php
$gTitulo = 'Guías e indicadores de Distribución';
$gIntro  = 'Reglas para asignar horarios sin empalmes.';
$gInd = [
    ['Horarios registrados', count($horarios), 'bi-calendar-week-fill'],
    ['Terapeutas', count($terapeutas), 'bi-person-workspace'],
    ['Días de atención', count($dias), 'bi-calendar-check-fill'],
];
$gGuias = [
    ['Asignar un horario', 'bi-calendar-plus', [
        'Elige terapeuta, paciente y día de la semana.',
        'Indica la hora de inicio y la de fin.',
        'La hora de fin debe ser posterior a la de inicio.',
        'Se atiende de lunes a sábado.',
    ]],
    ['Empalmes', 'bi-exclamation-triangle', [
        'Un terapeuta <strong>no puede tener dos horarios que se traslapen</strong> el mismo día.',
        'Si hay conflicto, el sistema avisa y no guarda el horario.',
        'Cambia la hora o el día e inténtalo de nuevo.',
    ]],
    ['Buenas prácticas', 'bi-lightbulb', [
        'Deja un margen entre sesiones para el cambio de paciente.',
        'Revisa la distribución semanal para equilibrar la carga.',
        'Elimina los horarios que ya no se usen.',
    ]],
];
@endphp
@include('layouts.guias', ['titulo' => $gTitulo, 'intro' => $gIntro, 'indicadores' => $gInd, 'guias' => $gGuias])
        </div>

</div>

<!-- ================= MODAL EDITAR ================= -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered"><div class="modal-content">
<form id="formEditar" method="POST" action="#">
    @csrf @method('PUT')
    <div class="modal-header text-white" style="background:var(--primary);">
        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Editar Horario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-bold text-secondary small">Paciente</label>
            <select name="paciente" id="edPaciente" class="form-select" required>
                @foreach(($listaPacientes ?? []) as $p)<option value="{{ $p->nombre }}">{{ $p->nombre }}</option>@endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold text-secondary small">Terapeuta</label>
            <select name="id_usuario" id="edTerapeuta" class="form-select" required>
                @foreach($terapeutas as $t)<option value="{{ $t->id_usuario }}">{{ $t->nombre }} {{ $t->apaterno }}</option>@endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold text-secondary small">Día de la semana</label>
            <select name="dia_semana" id="edDia" class="form-select" required>
                @foreach($dias as $d)<option>{{ $d }}</option>@endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-6"><label class="form-label fw-bold text-secondary small">Hora de inicio</label>
                <input type="time" name="hora_inicio" id="edInicio" class="form-control" min="07:00" max="19:59" required></div>
            <div class="col-6"><label class="form-label fw-bold text-secondary small">Hora de fin</label>
                <input type="time" name="hora_fin" id="edFin" class="form-control" min="07:01" max="20:00" required></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-divm"><i class="bi bi-check2-circle me-1"></i> Guardar cambios</button>
    </div>
</form>
</div></div>
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
// Al cambiar de semana se vuelve a abrir la pestaña Distribución
if (/[?&]semana=/.test(location.search)) { window.addEventListener('load', function () { var t = document.getElementById('distribucion-tab'); if (t) t.click(); }); }
</script>
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

function editarHorario(fila) {
    const d = fila.dataset;
    const sel = (id, v) => {
        const el = document.getElementById(id);
        if (![...el.options].some(o => o.value === v)) el.add(new Option(v, v)); // paciente dado de baja
        el.value = v;
    };
    sel('edPaciente', d.paciente); sel('edTerapeuta', d.usuario); sel('edDia', d.dia);
    document.getElementById('edInicio').value = d.inicio;
    document.getElementById('edFin').value = d.fin;
    document.getElementById('formEditar').action = "{{ url('/distribucion') }}/" + d.id;
    new bootstrap.Modal(document.getElementById('modalEditar')).show();
}

// Calendario: ver solo un terapeuta
document.querySelectorAll('[data-filtro-terapeuta]').forEach(b => b.addEventListener('click', () => {
    const id = b.dataset.filtroTerapeuta;
    document.querySelectorAll('.cal-event').forEach(e => {
        e.style.display = (!id || e.dataset.terapeutaId === id) ? '' : 'none';
    });
}));

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
