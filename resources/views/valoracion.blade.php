@extends('layouts.app')

@section('title', 'Informes de Valoración - DIVM')


@section('modulo', 'Módulo Valoración')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'enviar', 'icono' => 'bi-clipboard2-plus', 'texto' => 'Registrar Informe', 'activo' => true])
@include('layouts.tab', ['id' => 'historial', 'icono' => 'bi-clock-history', 'texto' => 'Historial'])
@include('layouts.tab', ['id' => 'diagnosticos', 'icono' => 'bi-lightning-charge', 'texto' => 'Diagnósticos Rápidos'])
@include('layouts.tab', ['id' => 'guias', 'icono' => 'bi-book', 'texto' => 'Guías'])
@endsection

@section('content')
    
    
    

    
    

    

   

    <!-- CONTENIDO -->
    <div class="tab-content" id="v-pills-tabContent">

        <!-- ============================= -->
        <!-- REGISTRAR INFORME -->
        <!-- ============================= -->

        <div class="tab-pane fade show active" id="panel-enviar">

            <div class="tab-pane fade" id="panel-diagnosticos" role="tabpanel">
<div class="container-fluid mt-4">

                <div class="row justify-content-center">

                    <div class="col-md-11 bg-white p-4 rounded shadow-sm">

                        <div class="titulo-banner">

                            <h3 class="mb-0">
                                <i class="bi bi-file-earmark-medical"></i>
                                Registrar Informe de Valoración
                            </h3>

                        </div>

                        <form action="{{ route('valoracion.store') }}" method="POST">
@csrf

                            <!-- ASIGNACIÓN -->
                            <span class="section-label">
                                Asignación del Paciente
                            </span>

                            <div class="input-group mb-3">

                                <span class="input-group-text">
                                    <i class="bi bi-person-fill me-2"></i>
                                    Paciente:
                                </span>

                                <select name="paciente_id" class="form-select" required>
<option value="" disabled selected>Seleccionar Paciente...</option>
@foreach($pacientes as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach
</select>

                            </div>

                            <!-- DATOS DE EVALUACIÓN -->
                            <span class="section-label">
                                1. Datos de Evaluación Inicial
                            </span>

                            <div class="row mb-3">

                                <div class="col-md-6">

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Prueba Aplicada:
                                        </span>

                                        <input name="prueba_aplicada" type="text"
                                               class="form-control"
                                               placeholder="Ej. Escala Wechsler, DSM-5">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Aplicadora:
                                        </span>

                                        <input name="aplicadora" type="text"
                                               class="form-control"
                                               placeholder="Nombre del especialista">

                                    </div>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="area-label">
                                    Motivo de la Evaluación
                                </label>

                                <textarea name="motivo_evaluacion" class="form-control"
                                          rows="3"
                                          placeholder="Describa la razón o justificación del informe..."></textarea>

                            </div>

                            <!-- HISTORIA CLÍNICA -->
                            <span class="section-label">
                                2. Antecedentes Médicos e Historia Clínica
                            </span>

                            <div class="row mb-3">

                                <div class="col-md-6">

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Gestación:
                                        </span>

                                        <input name="hc_gestacion" type="text"
                                               class="form-control"
                                               placeholder="Ej. Normoevolutiva, semanas, complicaciones">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Talla al Nacer:
                                        </span>

                                        <input name="hc_talla_nacer" type="text"
                                               class="form-control"
                                               placeholder="Ej. 50 cm / 3.200 kg">

                                    </div>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="area-label">
                                    Diagnóstico Clínico / Técnico
                                </label>

                                <textarea name="hc_diagnostico" class="form-control"
                                          rows="2"
                                          placeholder="Diagnóstico presuntivo o definitivo..."></textarea>

                            </div>

                            <!-- ÁREAS DE DESARROLLO -->
                            <span class="section-label">
                                3. Resultados del Análisis por Áreas de Desarrollo
                            </span>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="area-label">
                                        Desarrollo Cognitivo
                                    </label>

                                    <textarea name="des_cognitivo" class="form-control"
                                              rows="3"
                                              placeholder="Procesamiento, atención, memoria..."></textarea>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="area-label">
                                        Desarrollo Motor
                                    </label>

                                    <textarea name="des_motor" class="form-control"
                                              rows="3"
                                              placeholder="Motricidad fina y gruesa..."></textarea>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="area-label">
                                        Desarrollo Social
                                    </label>

                                    <textarea name="des_social" class="form-control"
                                              rows="3"
                                              placeholder="Interacción, pares, autoridad..."></textarea>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="area-label">
                                        Resultado de Lenguaje
                                    </label>

                                    <textarea name="res_lenguaje" class="form-control"
                                              rows="3"
                                              placeholder="Comprensivo, expresivo, fonológico..."></textarea>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="area-label">
                                    Conducta Observada en la Valoración
                                </label>

                                <textarea name="res_conducta_valoracion" class="form-control"
                                          rows="2"
                                          placeholder="Comportamiento durante las sesiones de prueba..."></textarea>

                            </div>

                            <!-- INDICACIONES -->
                            <span class="section-label">
                                4. Indicaciones y Líneas de Acción Recomendadas
                            </span>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="area-label">
                                        Indicaciones Escolares
                                    </label>

                                    <textarea name="ind_escolares" class="form-control"
                                              rows="3"
                                              placeholder="Adecuaciones curriculares en aula..."></textarea>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="area-label">
                                        Indicaciones Clínicas
                                    </label>

                                    <textarea name="ind_clinicas" class="form-control"
                                              rows="3"
                                              placeholder="Indicaciones médicas..."></textarea>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="area-label">
                                        Indicaciones Familiares
                                    </label>

                                    <textarea name="ind_familiares" class="form-control"
                                              rows="3"
                                              placeholder="Pautas de crianza o dinámicas en el hogar..."></textarea>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="area-label">
                                        Indicaciones Rehabilitadoras
                                    </label>

                                    <textarea name="ind_rehabilitadores" class="form-control"
                                              rows="3"
                                              placeholder="Terapias físicas, de lenguaje, ocupacionales..."></textarea>

                                </div>

                            </div>

                            <!-- BOTÓN VISUAL -->
                            <div class="text-center mt-4">

                                <button type="submit"
                                        class="btn btn-custom px-5 py-3 w-100 shadow-sm">

                                    GUARDAR INFORME DE VALORACIÓN COMPLETO

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
</div>

        </div>

        <!-- ============================= -->
        <!-- HISTORIAL -->
        <!-- ============================= -->

        <div class="tab-pane fade" id="panel-historial" role="tabpanel">
<div class="container-fluid mt-4">

            <div class="row justify-content-center">

                <div class="col-md-11 card-table">

                    <span class="section-label">
                        <i class="bi bi-journal-check"></i>
                        Historial de Informes de Valoración Emitidos
                    </span>

                    <div class="table-responsive mt-3">

                        <table class="table table-hover bg-white rounded">

                            <thead class="table-dark">

                                <tr>

                                    <th>Paciente</th>
                                    <th>Prueba Aplicada</th>
                                    <th>Especialista / Aplicadora</th>
                                    <th>Fecha Registro</th>
                                    <th class="text-center">Acciones</th>

                                </tr>

                            </thead>

                            <tbody>
@foreach($valoraciones as $v)
@php $f = $v->fecha_registro ?? $v->created_at ?? null; @endphp
<tr>
<td class="align-middle fw-bold">{{ $v->paciente_nombre ?? $v->paciente ?? '' }}</td>
<td class="align-middle">{{ $v->prueba_aplicada ?? '' }}</td>
<td class="align-middle">{{ $v->aplicadora ?? '' }}</td>
<td class="align-middle">{{ $f ? \Carbon\Carbon::parse($f)->format('d/m/Y H:i') : '' }}</td>
<td class="text-center align-middle"><a href="{{ url('/pdf/valoracion/'.$v->id) }}" target="_blank" class="btn btn-sm btn-generar me-1" title="Descargar / imprimir PDF"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</a></td>
</tr>
@endforeach
</tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
</div>

        <!-- ============================= -->
        <!-- DIAGNÓSTICOS RÁPIDOS -->
        <!-- ============================= -->

        <div class="container-fluid mt-4">

            <div class="row justify-content-center">

                <div class="col-md-11 bg-white p-4 rounded shadow-sm">

                    <span class="section-label">

                        <i class="bi bi-funnel-fill"></i>
                        Vista de Diagnósticos Rápidos

                    </span>

                    <div class="table-responsive mt-4">

                        <table class="table table-striped table-hover rounded border">

                            <thead style="background-color: #54063e; color: white;">

                                <tr>

                                    <th>Paciente</th>
                                    <th>Prueba Aplicada</th>
                                    <th>Diagnóstico Clínico</th>
                                    <th>Indicaciones Escolares Cortas</th>

                                </tr>

                            </thead>

                            <tbody>
@foreach($valoraciones as $v)
<tr>
<td class="fw-bold align-middle">{{ $v->paciente_nombre ?? $v->paciente ?? '' }}</td>
<td class="align-middle"><span class="badge" style="background-color: #84063e;">{{ $v->prueba_aplicada ?? '' }}</span></td>
<td class="align-middle">{{ $v->hc_diagnostico ?? '' }}</td>
<td class="small">{{ $v->ind_escolares ?? '' }}</td>
</tr>
@endforeach
</tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <!-- ============================= -->
        <!-- GUÍAS -->
        <!-- ============================= -->

        <div class="tab-pane fade" id="panel-guias" role="tabpanel">
<div class="container-fluid mt-4 mb-5">

            <div class="row justify-content-center">

                <div class="col-md-11 bg-white p-4 rounded shadow-sm">

                    <span class="section-label">

                        <i class="bi bi-lightbulb-fill"></i>
                        Guías de Adecuación Curricular Informativas

                    </span>

                    <p class="text-muted mb-4">

                        Manual de estrategias y recomendaciones para apoyar
                        las actividades dentro del aula escolar.

                    </p>

                    <div class="accordion" id="acordeonGuias">

                        <!-- GUÍA 1 -->

                        <div class="accordion-item">

                            <h2 class="accordion-header">

                                <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#guiaUno">

                                    <i class="bi bi-brightness-high-fill me-2 text-warning"></i>

                                    Adecuaciones de Acceso
                                    (Infraestructura y Materiales)

                                </button>

                            </h2>

                            <div id="guiaUno"
                                 class="accordion-collapse collapse show">

                                <div class="accordion-body">

                                    <p>
                                        Modificaciones en el espacio físico
                                        o utilización de recursos que permitan
                                        al alumno participar en las actividades.
                                    </p>

                                    <ul>

                                        <li>
                                            <strong>Ubicación estratégica:</strong>
                                            colocar al alumno cerca del docente
                                            o lejos de distractores.
                                        </li>

                                        <li>
                                            <strong>Materiales adaptados:</strong>
                                            utilizar apoyos visuales y materiales
                                            adecuados a sus necesidades.
                                        </li>

                                        <li>
                                            <strong>Gestión del tiempo:</strong>
                                            dividir las tareas complejas en
                                            pasos pequeños.
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                        <!-- GUÍA 2 -->

                        <div class="accordion-item">

                            <h2 class="accordion-header">

                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#guiaDos">

                                    <i class="bi bi-gear-wide-connected me-2 text-primary"></i>

                                    Adecuaciones Metodológicas
                                    (¿Cómo enseñar?)

                                </button>

                            </h2>

                            <div id="guiaDos"
                                 class="accordion-collapse collapse">

                                <div class="accordion-body">

                                    <p>
                                        Cambios en la forma de presentar
                                        los contenidos y explicar las actividades.
                                    </p>

                                    <ul>

                                        <li>
                                            <strong>Apoyos visuales:</strong>
                                            acompañar las indicaciones con
                                            imágenes o pictogramas.
                                        </li>

                                        <li>
                                            <strong>Instrucciones cortas:</strong>
                                            explicar las actividades paso a paso.
                                        </li>

                                        <li>
                                            <strong>Apoyo entre compañeros:</strong>
                                            permitir actividades colaborativas
                                            cuando sea conveniente.
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                        <!-- GUÍA 3 -->

                        <div class="accordion-item">

                            <h2 class="accordion-header">

                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#guiaTres">

                                    <i class="bi bi-file-earmark-check-fill me-2 text-success"></i>

                                    Adecuaciones en la Evaluación
                                    (¿Cómo medir el avance?)

                                </button>

                            </h2>

                            <div id="guiaTres"
                                 class="accordion-collapse collapse">

                                <div class="accordion-body">

                                    <p>
                                        Estrategias para realizar evaluaciones
                                        tomando en cuenta las necesidades
                                        de cada alumno.
                                    </p>

                                    <ul>

                                        <li>
                                            <strong>Formatos alternativos:</strong>
                                            utilizar evaluaciones orales,
                                            prácticas o mediante imágenes.
                                        </li>

                                        <li>
                                            <strong>Dosificación:</strong>
                                            reducir la cantidad de actividades
                                            por sesión cuando sea necesario.
                                        </li>

                                        <li>
                                            <strong>Criterios flexibles:</strong>
                                            considerar el aprendizaje y el
                                            desempeño de acuerdo con la actividad.
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
</div>

    </div>

    <!-- Bootstrap solamente para elementos visuales como el menú y acordeón -->
    
@endsection
