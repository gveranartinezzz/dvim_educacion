@extends('layouts.app')

@section('title', 'Recomendaciones Escolares - DIVM')


@section('modulo', 'Módulo Recomendaciones')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'enviar', 'icono' => 'bi-send-fill', 'texto' => 'Enviar Nota', 'activo' => true])
@include('layouts.tab', ['id' => 'banco', 'icono' => 'bi-archive-fill', 'texto' => 'Banco de Estrategias'])
@include('layouts.tab', ['id' => 'filtros', 'icono' => 'bi-funnel-fill', 'texto' => 'Búsqueda por Grado'])
@include('layouts.tab', ['id' => 'guias', 'icono' => 'bi-book-fill', 'texto' => 'Guías de Adecuación'])
@endsection

@section('content')
    
    
    
    
    
    

    

    <!-- CONTENEDOR DE PESTAÑAS -->
    <div class="tab-content container pb-5" id="v-pills-tabContent">
        
        <!-- ========================================================= -->
        <!-- PESTAÑA 1: ENVIAR NOTA / REGISTRAR (id: panel-enviar)     -->
        <!-- ========================================================= -->
        <div class="tab-pane fade show active" id="panel-enviar" role="tabpanel" aria-labelledby="enviar-tab">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-11 bg-white p-4 rounded shadow-sm">
                        
                        <div class="titulo-banner">
                            <h3 class="mb-0"><i class="bi bi-chat-left-text"></i> Registrar Recomendación Escolar</h3>
                        </div>

                        <form action="{{ route('recomendaciones.store') }}" method="POST">
@csrf
                            
                            <span class="section-label">Identificación del Alumno</span>
                            
                            <div class="input-group mb-2">
                                <span class="input-group-text">Paciente:</span>
                                <input type="text" name="paciente" class="form-control" placeholder="Nombre completo" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Madre:</span>
                                        <input type="text" name="madre" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Padre:</span>
                                        <input type="text" name="padre" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <span class="section-label mt-4">Información Académica</span>
                            
                            <div class="row mb-4">
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <span class="input-group-text">Escuela:</span>
                                        <input type="text" name="escuela_procedencia" class="form-control" placeholder="Nombre de la institución">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text" style="width: 100px;">Grado:</span>
                                        <select name="grado" class="form-select">
                                            <option value="" selected disabled>Elegir...</option>
                                            <option value="Estancia">Estancia</option>
                                            <option value="Preescolar">Preescolar</option>
                                            <option value="Primaria">Primaria</option>
                                            <option value="Secundaria">Secundaria</option>
                                            <option value="Preparatoria">Preparatoria</option>
                                            <option value="Universidad">Universidad</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <span class="section-label">Detalles de la Sugerencia</span>

                            <div class="mb-3">
                                <label class="area-label">Recomendación Pedagógica</label>
                                <textarea name="recomendacion" class="form-control" rows="4" placeholder="Describa las sugerencias aquí..." required></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="area-label">Notas Internas</label>
                                <textarea name="notas" class="form-control" rows="2" placeholder="Observaciones adicionales para el personal..."></textarea>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-custom px-5 py-3 w-100 shadow-sm">
                                    GUARDAR RECOMENDACIÓN ESCOLAR
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- PESTAÑA 2: BANCO DE ESTRATEGIAS / ARCHIVO (id: panel-banco)-->
        <!-- ========================================================= -->
        <div class="tab-pane fade" id="panel-banco" role="tabpanel" aria-labelledby="banco-tab">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-11 card-table">
                        <span class="section-label"><i class="bi bi-journal-check"></i> Recomendaciones Escolares Emitidas</span>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover bg-white rounded" id="tabla_recomendaciones">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Paciente</th>
                                        <th>Grado</th>
                                        <th>Escuela</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
@foreach($recomendaciones as $r)
                                    <tr>
                                        <td class="align-middle col-nombre">{{ $r->paciente }}</td>
                                        <td class="align-middle">{{ $r->grado }}</td>
                                        <td class="align-middle">{{ $r->escuela_procedencia }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ url('/pdf/recomendacion/'.$r->id) }}" target="_blank" class="btn btn-sm btn-generar me-1" title="Descargar / imprimir PDF"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</a>
                                            @if(session('rol') === 'Administrador')
<a href="{{ url('/recomendaciones/'.$r->id.'/editar') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Editar</a>
<form action="{{ url('/recomendaciones/'.$r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
@endif
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

        <!-- ========================================================= -->
        <!-- PESTAÑA 3: BÚSQUEDA POR GRADO (id: panel-filtros)         -->
        <!-- ========================================================= -->
        <div class="tab-pane fade" id="panel-filtros" role="tabpanel" aria-labelledby="filtros-tab">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-11 bg-white p-4 rounded shadow-sm">
                        <span class="section-label"><i class="bi bi-funnel-fill"></i> Filtrar Historial por Nivel Educativo</span>
                        
                        <!-- Tarjetas de selección rápida de grado -->
                        <div class="row row-cols-2 row-cols-md-4 g-3 my-2 text-center">
                            <div class="col">
                                <div class="card h-100 bg-light grado-card active-filter" data-grado="todos">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="bi bi-grid-fill fs-3 mb-2 text-primary"></i>
                                        <h6 class="card-title mb-0 fw-bold">Todos</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card h-100 bg-light grado-card" data-grado="Preescolar">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="bi bi-egg-fried fs-3 mb-2 text-warning"></i>
                                        <h6 class="card-title mb-1 fw-bold">Preescolar</h6>
                                        <small class="text-muted">Y Estancia</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card h-100 bg-light grado-card" data-grado="Primaria">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="bi bi-book fs-3 mb-2 text-success"></i>
                                        <h6 class="card-title mb-0 fw-bold">Primaria</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card h-100 bg-light grado-card" data-grado="Secundaria">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="bi bi-pencil-ruler fs-3 mb-2 text-danger"></i>
                                        <h6 class="card-title mb-0 fw-bold">Secundaria</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de visualización rápida filtrada -->
                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-hover rounded border" id="tabla_grados">
                                <thead style="background-color: #54063e; color: white;">
                                    <tr>
                                        <th>Paciente</th>
                                        <th>Grado Académico</th>
                                        <th>Institución de Procedencia</th>
                                        <th>Última Observación Pedagógica</th>
                                    </tr>
                                </thead>
                                <tbody>
@foreach($recomendaciones as $r)
                                    <tr data-categoria="{{ $r->grado }}">
                                        <td class="fw-bold align-middle">{{ $r->paciente }}</td>
                                        <td class="align-middle"><span class="badge" style="background-color: #84063e;">{{ $r->grado }}</span></td>
                                        <td class="align-middle">{{ $r->escuela_procedencia }}</td>
                                        <td class="small text-truncate" style="max-width: 300px;">{{ $r->recomendacion }}</td>
                                    </tr>
                                    @endforeach
</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- PESTAÑA 4: GUÍAS DE ADECUACIÓN (id: panel-guias)          -->
        <!-- ========================================================= -->
        <div class="tab-pane fade" id="panel-guias" role="tabpanel" aria-labelledby="guias-tab">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-11 bg-white p-4 rounded shadow-sm">
                        <span class="section-label"><i class="bi bi-lightbulb-fill"></i> Guías de Adecuación Curricular Informativas</span>
                        <p class="text-muted mb-4">Manual técnico de estrategias estandarizadas del centro para la intervención en el aula escolar.</p>
                        
                        <div class="accordion" id="acordeonGuias">
                            
                            <!-- Guía 1 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingUno">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#guiaUno" aria-expanded="true" aria-controls="guiaUno">
                                        <i class="bi bi-brightness-high-fill me-2 text-warning"></i> Adecuaciones de Acceso (Infraestructura y Materiales)
                                    </button>
                                </h2>
                                <div id="guiaUno" class="accordion-collapse collapse show" aria-labelledby="headingUno" data-bs-parent="#acordeonGuias">
                                    <div class="accordion-body">
                                        <p>Modificaciones en el espacio físico o provisión de recursos específicos que permitan al alumno participar en las actividades:</p>
                                        <ul>
                                            <li><strong>Ubicación estratégica:</strong> Sentar al alumno cerca del docente o lejos de estímulos distractores (ventanas, pasillos).</li>
                                            <li><strong>Materiales adaptados:</strong> Macrotipos, organizadores visuales impresos, empuñaduras gruesas para lápices.</li>
                                            <li><strong>Gestión del tiempo:</strong> Fragmentar las tareas complejas en pasos pequeños y otorgar tiempos de descanso regulados.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Guía 2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingDos">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guiaDos" aria-expanded="false" aria-controls="guiaDos">
                                        <i class="bi bi-gear-wide-connected me-2 text-primary"></i> Adecuaciones Metodológicas (¿Cómo enseñar?)
                                    </button>
                                </h2>
                                <div id="guiaDos" class="accordion-collapse collapse" aria-labelledby="headingDos" data-bs-parent="#acordeonGuias">
                                    <div class="accordion-body">
                                        <p>Cambios en la forma en que el docente transmite los contenidos del programa educativo:</p>
                                        <ul>
                                            <li><strong>Canales multisensoriales:</strong> Acompañar las indicaciones verbales con apoyos visuales explícitos (pictogramas, listas de cotejo con dibujos).</li>
                                            <li><strong>Instrucciones cortas:</strong> Explicaciones directas, paso a paso, verificando comprensión pidiéndole al alumno que repita la instrucción.</li>
                                            <li><strong>Tutoría entre iguales:</strong> Asignar temporalmente a un compañero monitor que sirva de guía social y académica.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Guía 3 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTres">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guiaTres" aria-expanded="false" aria-controls="guiaTres">
                                        <i class="bi bi-file-earmark-check-fill me-2 text-success"></i> Adecuaciones en la Evaluación (¿Cómo medir el avance?)
                                    </button>
                                </h2>
                                <div id="guiaTres" class="accordion-collapse collapse" aria-labelledby="headingTres" data-bs-parent="#acordeonGuias">
                                    <div class="accordion-body">
                                        <p>Estrategias recomendadas para evaluar el desempeño de manera justa según la condición pedagógica:</p>
                                        <ul>
                                            <li><strong>Formatos alternativos:</strong> Permitir evaluaciones orales, proyectos prácticos o respuestas mediante selección por imágenes en vez de textos largos.</li>
                                            <li><strong>Dosificación:</strong> Reducir el número de reactivos por hoja o permitir que realice el examen en dos sesiones.</li>
                                            <li><strong>Criterios flexibles:</strong> Priorizar el fondo conceptual del aprendizaje sobre la caligrafía o la velocidad de ejecución.</li>
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

<!-- SCRIPTS JAVASCRIPT -->
@endsection
