@extends('layouts.app')

@section('title', 'Plan de Intervención para Padres - DIVM')


@section('modulo', 'Módulo Planes')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'nuevo', 'icono' => 'bi-journal-plus', 'texto' => 'Nuevo Plan', 'activo' => true])
@include('layouts.tab', ['id' => 'lista', 'icono' => 'bi-list-ul', 'texto' => 'Planes Registrados'])
@include('layouts.tab', ['id' => 'guias', 'icono' => 'bi-journal-bookmark-fill', 'texto' => 'Guías e Indicadores'])
@endsection

@section('content')
    
    
    
    <!-- Bootstrap CSS -->
    
    
    

    <div class="tab-content">
<div class="tab-pane fade show active" id="panel-nuevo" role="tabpanel">
<div class="container my-5">
        <div class="form-container mx-auto">
            <div class="titulo-banner">

                    <h3>
                        <i class="bi bi-journal-text"></i>
                        Plan de Intervención para Padres
                    </h3>

                    <p class="mb-0 small">
                        Actividades por área que la familia practicará en casa con el paciente
                    </p>

                </div>

            <form action="{{ route('planes.store') }}" method="POST">
@csrf
                
                <!-- Identificación del Paciente -->
                <div class="mb-4">
                    <h4 class="etiqueta-seccion mb-4">Identificación del Paciente</h4>
                    
                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            @include('layouts.paciente_select', ['clase' => ''])
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text input-grupo-texto">Padre:</span>
                                <input type="text" name="padre" class="form-control" placeholder="Nombre del padre o tutor">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text input-grupo-texto">Madre:</span>
                                <input type="text" name="madre" class="form-control" placeholder="Nombre de la madre o tutora">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Áreas de Intervención Especializada -->
                <div class="mb-4">
                    <h4 class="etiqueta-seccion mb-4">Áreas de Intervención Especializada</h4>

                    <!-- Terapia Comunicativa -->
                    <div class="mb-4">
                        <label class="d-flex align-items-center mb-2 etiqueta-terapia">
                            Terapia Comunicativa
                        </label>
                        <textarea class="form-control" name="terapia_comunicativa" rows="3" placeholder="Escriba las recomendaciones aquí..."></textarea>
                    </div>

                    <!-- Terapia de Integración Sensorial -->
                    <div class="mb-4">
                        <label class="d-flex align-items-center mb-2 etiqueta-terapia">
                            Terapia de Integración Sensorial
                        </label>
                        <textarea class="form-control" name="terapia_sensorial" rows="3" placeholder="Escriba las recomendaciones aquí..."></textarea>
                    </div>

                    <!-- Terapia de Aprendizaje -->
                    <div class="mb-4">
                        <label class="d-flex align-items-center mb-2 etiqueta-terapia">
                            Terapia de Aprendizaje
                        </label>
                        <textarea class="form-control" name="terapia_aprendizaje" rows="3" placeholder="Escriba las recomendaciones aquí..."></textarea>
                    </div>

                    <!-- Terapia Ocupacional -->
                    <div class="mb-4">
                        <label class="d-flex align-items-center mb-2 etiqueta-terapia">
                            Terapia Ocupacional
                        </label>
                        <textarea class="form-control" name="terapia_ocupacional" rows="3" placeholder="Escriba las recomendaciones aquí..."></textarea>
                    </div>

                    <!-- Terapia de Atención y Concentración -->
                    <div class="mb-4">
                        <label class="d-flex align-items-center mb-2 etiqueta-terapia">
                            Terapia de Atención y Concentración
                        </label>
                        <textarea class="form-control" name="terapia_atencion" rows="3" placeholder="Escriba las recomendaciones aquí..."></textarea>
                    </div>

                    <!-- Terapia de Educación Emocional -->
                    <div class="mb-4">
                        <label class="d-flex align-items-center mb-2 etiqueta-terapia">
                            Terapia de Educación Emocional
                        </label>
                        <textarea class="form-control" name="terapia_emocional" rows="3" placeholder="Escriba las recomendaciones aquí..."></textarea>
                    </div>
                </div>

                <!-- Botón de Envío -->
                <div class="d-grid gap-2 mt-5">
                    <button type="submit" class="btn btn-generar btn-lg py-3 shadow-sm">
                         Guardar y Generar Plan de Intervención
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

    <div class="tab-pane fade" id="panel-lista" role="tabpanel">
<div class="container mb-5">
        <div class="form-container mx-auto">
            <h4 class="etiqueta-seccion mb-3">Planes registrados</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>Paciente</th><th>Padre</th><th>Madre</th><th class="text-end">Acciones</th></tr></thead>
                    <tbody>
                    @foreach($planes as $p)
                        <tr>
                            <td class="fw-bold">{{ $p->paciente }}</td><td>{{ $p->padre }}</td><td>{{ $p->madre }}</td>
                            <td class="text-end"><a href="{{ url('/pdf/plan/'.$p->id) }}" target="_blank" class="btn btn-sm btn-generar me-1" title="Descargar / imprimir PDF"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</a>
@if(session('rol') === 'Administrador')
<a href="{{ url('/plan/'.$p->id.'/editar') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Editar</a>
<form action="{{ url('/plan/'.$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
@endif </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
        <div class="tab-pane fade" id="panel-guias" role="tabpanel">
@php
$gTitulo = 'Guías e indicadores de Planes';
$gIntro  = 'Cómo armar un plan de intervención claro para la familia.';
$gInd = [
    ['Planes registrados', $planes->count(), 'bi-journal-text'],
    ['Pacientes con plan', $planes->pluck('paciente')->filter()->unique()->count(), 'bi-people-fill'],
    ['Con terapia comunicativa', $planes->filter(fn ($p) => filled($p->terapia_comunicativa))->count(), 'bi-chat-dots-fill'],
    ['Con terapia sensorial', $planes->filter(fn ($p) => filled($p->terapia_sensorial))->count(), 'bi-hand-index-thumb-fill'],
    ['Con terapia de aprendizaje', $planes->filter(fn ($p) => filled($p->terapia_aprendizaje))->count(), 'bi-mortarboard-fill'],
];
$gGuias = [
    ['Plan de intervención', 'bi-journal-text', [
        'Un plan por paciente, dirigido a la madre, el padre o el tutor.',
        'Define actividades claras para hacer en casa en cada área.',
        'Usa un lenguaje sencillo, sin tecnicismos.',
    ]],
    ['Áreas de terapia', 'bi-puzzle', [
        '<strong>Comunicativa:</strong> lenguaje y comunicación funcional.',
        '<strong>Sensorial</strong> y <strong>ocupacional:</strong> regulación y autonomía en la vida diaria.',
        '<strong>Aprendizaje</strong> y <strong>atención y concentración:</strong> hábitos y tareas escolares.',
        '<strong>Educación emocional:</strong> manejo de emociones y conducta.',
    ]],
    ['Cómo redactar objetivos', 'bi-bullseye', [
        'Que sean observables y medibles.',
        'Incluye la frecuencia, por ejemplo 10 minutos diarios.',
        'Revisa el avance en cada sesión y ajusta el plan.',
    ]],
];
@endphp
@include('layouts.guias', ['titulo' => $gTitulo, 'intro' => $gIntro, 'indicadores' => $gInd, 'guias' => $gGuias])
        </div>
</div>

    <!-- Bootstrap JS Bundle -->
    
@endsection
