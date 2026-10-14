@extends('layouts.app')

@section('title', 'Bitácora de Avances Pedagógicos - DIVM')


@section('modulo', 'Módulo Avances')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'enviar', 'icono' => 'bi-journal-plus', 'texto' => 'Registrar Avance', 'activo' => true])
@include('layouts.tab', ['id' => 'historial', 'icono' => 'bi-clock-history', 'texto' => 'Historial de Sesiones'])
@include('layouts.tab', ['id' => 'expediente', 'icono' => 'bi-folder2-open', 'texto' => 'Expediente Pedagógico'])
@endsection

@section('content')
    
    
    

    
    

    

<div class="container-fluid py-4">

    <div class="tab-content">

        <!-- ========================================== -->
        <!-- REGISTRAR AVANCE DIARIO -->
        <!-- ========================================== -->

        <div class="tab-pane fade show active" id="panel-enviar">

            <div class="row justify-content-center">

                <div class="col-md-11 bg-white p-4 rounded shadow-sm">

                    <div class="titulo-banner">

                        <h3>
                            <i class="bi bi-clipboard-pulse"></i>
                            Registro de Evolución y Avances Diarios
                        </h3>

                        <p class="mb-0 small">
                            Seguimiento psicopedagógico individualizado para alumnos con discapacidad
                        </p>

                    </div>

                    <form action="{{ route('avances.store') }}" method="POST">
@csrf

                        <span class="section-label">
                            <i class="bi bi-person-badge"></i>
                            Datos de Control
                        </span>

                        <div class="input-group mb-2">

                            <span class="input-group-text">
                                Seleccionar Alumno
                            </span>

                            <select name="paciente_id"
                                    class="form-select"
                                    required>

                                <option value="" disabled selected>
                                    Seleccione el alumno...
                                </option>
@foreach($pacientes as $p)<option value="{{ $p->id }}" {{ old('paciente_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>@endforeach
</select>

                        </div>

                        <div class="input-group mb-2">

                            <span class="input-group-text">
                                Fecha de Sesión
                            </span>

                            <input type="date"
                                   name="fecha_sesion"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="row">

                            <div class="col-md-6">

                                <div class="input-group mb-2">

                                    <span class="input-group-text"
                                          style="width:120px;">
                                        Hora Inicio
                                    </span>

                                    <input type="time"
                                           name="hora_inicio"
                                           class="form-control"
                                           required>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="input-group mb-2">

                                    <span class="input-group-text"
                                          style="width:120px;">
                                        Hora Fin
                                    </span>

                                    <input type="time"
                                           name="hora_fin"
                                           class="form-control"
                                           required>

                                </div>

                            </div>

                        </div>

                        <div class="input-group mb-2">

                            <span class="input-group-text">
                                Estatus de la Sesión
                            </span>

                            <select name="estatus_sesion"
                                    class="form-select"
                                    required>

                                <option value="Asistió">
                                    Asistió con éxito
                                </option>

                                <option value="Falta Justificada">
                                    Inasistencia Justificada
                                </option>

                                <option value="Falta Injustificada">
                                    Inasistencia Injustificada
                                </option>

                                <option value="Cancelada">
                                    Sesión Cancelada por la Institución
                                </option>

                            </select>

                        </div>

                        <span class="section-label">

                            <i class="bi bi-journal-text"></i>

                            Evaluación Cualitativa e Intervención

                        </span>

                        <div class="mb-3">

                            <label class="form-label fw-bold text-secondary small">

                                1. Actividades y Áreas Trabajadas:

                            </label>

                            <textarea name="actividades_realizadas"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Describa las dinámicas, materiales terapéuticos ocupados y adecuaciones curriculares aplicadas..."
                                      required></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-bold text-secondary small">

                                2. Observaciones Conductuales y Estado Emocional:

                            </label>

                            <textarea name="observaciones_comportamiento"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Nivel de tolerancia a la frustración, respuestas a estímulos sensoriales, periodos de atención o fatiga..."
                                      required></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-bold text-secondary small">

                                3. Logros Obtenidos y Nivel de Apoyo Requerido:

                            </label>

                            <textarea name="logros_obtenidos"
                                      class="form-control"
                                      rows="3"
                                      placeholder="¿Qué objetivos de su Plan Pedagógico alcanzó hoy?"
                                      required></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-bold text-secondary small">

                                4. Tareas, Recomendaciones o Ajustes para el Hogar:

                            </label>

                            <textarea name="tareas_o_recomendaciones"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Actividades sugeridas para los tutores..."
                                      required></textarea>

                        </div>

                        <button type="submit"
                                class="btn btn-custom w-100 py-3 mt-2 shadow-sm">

                            <i class="bi bi-save2"></i>

                            GUARDAR AVANCE DIARIO

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- ========================================== -->
        <!-- HISTORIAL -->
        <!-- ========================================== -->

        <div class="tab-pane fade" id="panel-historial" role="tabpanel">
<div class="tab-pane fade" id="panel-expediente" role="tabpanel">
<div class="mt-5">

            <div class="row justify-content-center">

                <div class="col-md-11 card-table">

                    <span class="section-label">

                        <i class="bi bi-clock-history"></i>

                        Historial General de Intervenciones

                    </span>

                    <div class="table-responsive mt-3">

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">

                                <tr>

                                    <th>Alumno / Paciente</th>

                                    <th>Especialista a Cargo</th>

                                    <th>Fecha de Atención</th>

                                    <th class="text-center">
                                        Estatus
                                    </th>

                                    <th class="text-center">
                                        Acciones
                                    </th>

                                </tr>

                            </thead>

                            <tbody>
@php $cls=['Asistió'=>'bg-success','Falta Justificada'=>'bg-warning text-dark','Falta Injustificada'=>'bg-danger','Cancelada'=>'bg-secondary']; @endphp
@foreach($avances as $a)
<tr>
<td class="fw-bold text-secondary">{{ $a->paciente_nombre }}</td>
<td><i class="bi bi-person-workspace"></i> {{ $a->especialista ?? '' }}</td>
<td>{{ !empty($a->fecha_sesion) ? \Carbon\Carbon::parse($a->fecha_sesion)->format('d/m/Y') : '' }}</td>
<td class="text-center"><span class="badge rounded-pill {{ $cls[$a->estatus_sesion] ?? 'bg-secondary' }} px-3 py-2">{{ $a->estatus_sesion }}</span></td>
<td class="text-center">
<a href="{{ url('/pdf/avance/'.$a->id) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="PDF"><i class="bi bi-file-earmark-pdf-fill"></i></a>
<button type="button" class="btn btn-sm btn-outline-primary ms-1" onclick="llenarModalEditar(this)"
 data-id="{{ $a->id }}" data-paciente="{{ $a->paciente_nombre }}" data-fecha="{{ $a->fecha_sesion ?? '' }}"
 data-estatus="{{ $a->estatus_sesion ?? '' }}" data-inicio="{{ substr($a->hora_inicio ?? '',0,5) }}" data-fin="{{ substr($a->hora_fin ?? '',0,5) }}"
 data-act="{{ $a->actividades_realizadas ?? '' }}" data-obs="{{ $a->observaciones_comportamiento ?? '' }}"
 data-logros="{{ $a->logros_obtenidos ?? '' }}" data-tareas="{{ $a->tareas_o_recomendaciones ?? '' }}">
<i class="bi bi-pencil-square"></i></button>
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
</div>

        <!-- ========================================== -->
        <!-- EXPEDIENTE -->
        <!-- ========================================== -->

        <div class="mt-5">

            <div class="row justify-content-center">

                <div class="col-md-11 bg-white p-4 rounded shadow-sm">

                    <span class="section-label">

                        <i class="bi bi-folder2-open"></i>

                        Expediente Clínico de Observaciones Detalladas

                    </span>

                    <div class="mt-3">

                        @foreach($avances as $a)
<div class="card-clinica p-4 mb-4 border rounded">
<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
<h5 class="mb-0 text-primary fw-bold"><i class="bi bi-file-earmark-person-fill"></i> {{ $a->paciente_nombre }}</h5>
<span class="badge bg-light text-dark border"><i class="bi bi-calendar-event"></i> Sesión del: {{ !empty($a->fecha_sesion) ? \Carbon\Carbon::parse($a->fecha_sesion)->format('d/m/Y') : '' }}</span>
</div>
<div class="row g-3">
<div class="col-md-6">
<p class="mb-2"><span class="d-block small text-muted fw-bold"><i class="bi bi-puzzle"></i> Intervención y Actividades:</span>{{ $a->actividades_realizadas ?? '' }}</p>
<p class="mb-2"><span class="d-block small text-muted fw-bold"><i class="bi bi-heart-pulse"></i> Aspectos Conductuales y Emocionales:</span>{{ $a->observaciones_comportamiento ?? '' }}</p>
</div>
<div class="col-md-6">
<p class="mb-2"><span class="d-block small text-muted fw-bold"><i class="bi bi-trophy"></i> Logros y Nivel de Autonomía:</span>{{ $a->logros_obtenidos ?? '' }}</p>
<p class="mb-2"><span class="d-block small text-muted fw-bold"><i class="bi bi-house-door"></i> Orientación para Familiares:</span>{{ $a->tareas_o_recomendaciones ?? '' }}</p>
</div>
</div>
</div>
@endforeach

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ========================================== -->
<!-- MODAL PARA EDITAR -->
<!-- ========================================== -->

<form id="formEditarAvance" method="POST">@csrf @method('PUT')</form>

<div class="modal fade"
     id="modalEditarAvance"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header text-white"
                 style="background:#54063e;">

                <h5 class="modal-title">

                    <i class="bi bi-pencil-square"></i>

                    Actualizar Avance Pedagógico

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body px-4">

                <span class="section-label mt-0">

                    <i class="bi bi-person-badge"></i>

                    Control de Sesión

                </span>

                <div class="mb-3">

                    <label class="form-label fw-bold small text-muted">

                        Alumno / Paciente

                    </label>

                    <input type="text"
                           id="edit_paciente_nombre"
                           class="form-control bg-light"
                           value="Juan Pérez"
                           readonly>

                </div>

                <div class="row g-2 mb-2">

                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-muted">

                            Fecha de Atención

                        </label>

                        <input type="date"
                               id="edit_fecha_sesion" name="fecha_sesion" form="formEditarAvance"
                               class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-muted">

                            Estatus de Sesión

                        </label>

                        <select id="edit_estatus_sesion" name="estatus_sesion" form="formEditarAvance"
                                class="form-select">

                            <option>Asistió</option>
                            <option>Falta Justificada</option>
                            <option>Falta Injustificada</option>
                            <option>Cancelada</option>

                        </select>

                    </div>

                </div>

                <div class="row g-2 mb-3">

                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-muted">

                            Hora Inicio

                        </label>

                        <input type="time"
                               id="edit_hora_inicio" name="hora_inicio" form="formEditarAvance"
                               class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-bold small text-muted">

                            Hora Fin

                        </label>

                        <input type="time"
                               id="edit_hora_fin" name="hora_fin" form="formEditarAvance"
                               class="form-control">

                    </div>

                </div>

                <span class="section-label">

                    <i class="bi bi-journal-text"></i>

                    Campos Cualitativos

                </span>

                <div class="mb-2">

                    <label class="form-label fw-bold text-secondary small">

                        1. Actividades y Áreas Trabajadas:

                    </label>

                    <textarea id="edit_actividades" name="actividades_realizadas" form="formEditarAvance"
                              class="form-control"
                              rows="3"></textarea>

                </div>

                <div class="mb-2">

                    <label class="form-label fw-bold text-secondary small">

                        2. Observaciones Conductuales y Emocionales:

                    </label>

                    <textarea id="edit_comportamiento" name="observaciones_comportamiento" form="formEditarAvance"
                              class="form-control"
                              rows="3"></textarea>

                </div>

                <div class="mb-2">

                    <label class="form-label fw-bold text-secondary small">

                        3. Logros Obtenidos y Nivel de Apoyo:

                    </label>

                    <textarea id="edit_logros" name="logros_obtenidos" form="formEditarAvance"
                              class="form-control"
                              rows="3"></textarea>

                </div>

                <div class="mb-2">

                    <label class="form-label fw-bold text-secondary small">

                        4. Tareas o Recomendaciones para Casa:

                    </label>

                    <textarea id="edit_tareas" name="tareas_o_recomendaciones" form="formEditarAvance"
                              class="form-control"
                              rows="3"></textarea>

                </div>

            </div>

            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-secondary fw-bold"
                        data-bs-dismiss="modal">

                    CANCELAR

                </button>

                <button type="submit" form="formEditarAvance"
                        class="btn btn-custom px-4">

                    ACTUALIZAR REGISTRO

                </button>

            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>

function llenarModalEditar(btn) {
    const d = btn.dataset;
    document.getElementById('formEditarAvance').action = "{{ url('/avances') }}/" + d.id;
    document.getElementById('edit_paciente_nombre').value = d.paciente;
    document.getElementById('edit_fecha_sesion').value = d.fecha;
    document.getElementById('edit_estatus_sesion').value = d.estatus;
    document.getElementById('edit_hora_inicio').value = d.inicio;
    document.getElementById('edit_hora_fin').value = d.fin;
    document.getElementById('edit_actividades').value = d.act;
    document.getElementById('edit_comportamiento').value = d.obs;
    document.getElementById('edit_logros').value = d.logros;
    document.getElementById('edit_tareas').value = d.tareas;
    new bootstrap.Modal(document.getElementById('modalEditarAvance')).show();
}

</script>
@endpush
