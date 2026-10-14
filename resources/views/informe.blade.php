@extends('layouts.app')

@section('title', 'Informe Pedagógico - DIVM')


@section('modulo', 'Módulo Informes')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'generar', 'icono' => 'bi-file-earmark-plus', 'texto' => 'Generar Informe', 'activo' => true])
@include('layouts.tab', ['id' => 'lista', 'icono' => 'bi-journal-check', 'texto' => 'Informes Registrados'])
@include('layouts.tab', ['id' => 'filtros', 'icono' => 'bi-funnel-fill', 'texto' => 'Búsqueda y Filtros'])
@include('layouts.tab', ['id' => 'graficas', 'icono' => 'bi-bar-chart-line', 'texto' => 'Estadísticas y Gráficas'])
@include('layouts.tab', ['id' => 'plantillas', 'icono' => 'bi-journal-bookmark-fill', 'texto' => 'Guías e Indicadores'])
@endsection

@section('content')
    
    
    

    
    

    

    <!-- ================= PANEL GENERAR ================= -->

    <div class="tab-content" id="v-pills-tabContent">

        <div class="tab-pane fade show active" id="panel-generar">

            <div class="container-fluid">
                <div class="row justify-content-center">

                    <div class="col-md-11 bg-white p-4 rounded shadow-sm">

                        <div class="titulo-banner">

                            <h3 class="mb-0">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Registro de Informe Pedagógico
                            </h3>

                        </div>

                        <form action="{{ route('informes.store') }}" method="POST">
@csrf

                            <!-- DATOS DE IDENTIFICACIÓN -->

                            <span class="section-label">
                                Datos de Identificación
                            </span>

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    Paciente:
                                </span>

                                <input type="text"
                                       name="paciente"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="row mb-3">

                                <div class="col-md-6">

                                    <span class="area-label">
                                        Datos del Padre
                                    </span>

                                    <input type="text"
                                           name="nombre_padre"
                                           class="form-control mb-2"
                                           placeholder="Nombre completo">

                                    <div class="row g-2">

                                        <div class="col-8">
                                            <input type="text"
                                                   name="grado_estudios_padre"
                                                   class="form-control"
                                                   placeholder="Estudios/Ocupación">
                                        </div>

                                        <div class="col-4">
                                            <input type="number"
                                                   name="edad_padre"
                                                   class="form-control"
                                                   placeholder="Edad">
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <span class="area-label">
                                        Datos de la Madre
                                    </span>

                                    <input type="text"
                                           name="nombre_madre"
                                           class="form-control mb-2"
                                           placeholder="Nombre completo">

                                    <div class="row g-2">

                                        <div class="col-8">
                                            <input type="text"
                                                   name="grado_estudios_madre"
                                                   class="form-control"
                                                   placeholder="Estudios/Ocupación">
                                        </div>

                                        <div class="col-4">
                                            <input type="number"
                                                   name="edad_madre"
                                                   class="form-control"
                                                   placeholder="Edad">
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row mb-4">

                                <div class="col-md-4">

                                    <div class="input-group">

                                        <span class="input-group-text"
                                              style="width:120px;">
                                            F. Nacimiento:
                                        </span>

                                        <input type="date"
                                               name="fecha_nacimiento"
                                               class="form-control">

                                    </div>

                                </div>

                                <div class="col-md-8">

                                    <div class="input-group">

                                        <span class="input-group-text"
                                              style="width:120px;">
                                            Domicilio:
                                        </span>

                                        <input type="text"
                                               name="domicilio"
                                               class="form-control">

                                    </div>

                                </div>

                            </div>

                            <!-- EVALUACIÓN -->

                            <span class="section-label">
                                Evaluación y Motivo
                            </span>

                            <div class="row mb-3">

                                <div class="col-md-6">

                                    <label class="area-label">
                                        Prueba Aplicada
                                    </label>

                                    <textarea name="prueba_aplicada"
                                              class="form-control"
                                              rows="2"></textarea>

                                </div>

                                <div class="col-md-6">

                                    <label class="area-label">
                                        Aplicadora
                                    </label>

                                    <textarea name="aplicadora"
                                              class="form-control"
                                              rows="2"></textarea>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="area-label">
                                    Motivo de la Evaluación
                                </label>

                                <textarea name="motivo_evaluacion"
                                          class="form-control"
                                          rows="2"></textarea>

                            </div>

                            <!-- HISTORIA CLÍNICA -->

                            <span class="section-label">
                                Historia Clínica y Resultados
                            </span>

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label class="fw-bold small">
                                        Gestación:
                                    </label>

                                    <input type="text"
                                           name="hc_gestacion"
                                           class="form-control">

                                </div>

                                <div class="col-md-4">

                                    <label class="fw-bold small">
                                        Talla/Peso:
                                    </label>

                                    <input type="text"
                                           name="hc_talla_nacer"
                                           class="form-control">

                                </div>

                                <div class="col-md-4">

                                    <label class="fw-bold small">
                                        Diagnóstico:
                                    </label>

                                    <input type="text"
                                           name="hc_diagnostico"
                                           class="form-control">

                                </div>

                                <div class="col-md-6">

                                    <label class="area-label">
                                        Lenguaje y Comunicación
                                    </label>

                                    <textarea name="res_lenguaje"
                                              class="form-control"
                                              rows="2"></textarea>

                                </div>

                                <div class="col-md-6">

                                    <label class="area-label">
                                        Conducta en Valoración
                                    </label>

                                    <textarea name="res_conducta_valoracion"
                                              class="form-control"
                                              rows="2"></textarea>

                                </div>

                            </div>

                            <!-- DESARROLLO -->

                            <span class="section-label">
                                Desarrollo y Áreas
                            </span>

                            <div class="row mb-3">

                                <div class="col-md-4">

                                    <label class="area-label">
                                        Cognitivo
                                    </label>

                                    <textarea name="des_cognitivo"
                                              class="form-control"
                                              rows="3"></textarea>

                                </div>

                                <div class="col-md-4">

                                    <label class="area-label">
                                        Motor
                                    </label>

                                    <textarea name="des_motor"
                                              class="form-control"
                                              rows="3"></textarea>

                                </div>

                                <div class="col-md-4">

                                    <label class="area-label">
                                        Social
                                    </label>

                                    <textarea name="des_social"
                                              class="form-control"
                                              rows="3"></textarea>

                                </div>

                            </div>

                            <!-- INDICACIONES -->

                            <span class="section-label">
                                Indicaciones Finales
                            </span>

                            <div class="row g-3 mb-4">

                                <div class="col-md-6">

                                    <label class="fw-bold small text-muted">
                                        CLÍNICAS / FAMILIARES:
                                    </label>

                                    <textarea name="ind_clinicas" class="form-control mb-2" rows="2" placeholder="Clínicas"></textarea>
                                    <textarea name="ind_familiares" class="form-control" rows="2" placeholder="Familiares"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold small text-muted">ESCOLARIDAD ACTUAL:</label>
                                    <select name="ind_escolares" class="form-select mb-3">
                                        <option value="Estancia">Estancia</option>
                                        <option value="Preescolar">Preescolar</option>
                                        <option value="Primaria">Primaria</option>
                                        <option value="Secundaria">Secundaria</option>
                                        <option value="Preparatoria">Preparatoria</option>
                                        <option value="Universidad">Universidad</option>
                                    </select>
                                    <label class="fw-bold small text-muted">REHABILITADORES:</label>
                                    <textarea name="ind_rehabilitadores" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 py-3 shadow-sm">GUARDAR INFORME</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<div class="tab-pane fade" id="panel-lista" role="tabpanel">
<div class="container-fluid mt-4 mb-5">
        <div class="row justify-content-center"><div class="col-md-11 card-table">
            <span class="section-label"><i class="bi bi-journal-check"></i> Informes registrados</span>
            <div class="table-responsive mt-3">
                <table class="table table-hover">
                    <thead class="table-dark"><tr><th>Paciente</th><th>Prueba</th><th>Aplicadora</th><th>Diagnóstico</th><th class="text-center">Acciones</th></tr></thead>
                    <tbody>
                    @foreach($informes as $i)
                        <tr>
                            <td class="fw-bold">{{ $i->paciente }}</td><td>{{ $i->prueba_aplicada }}</td><td>{{ $i->aplicadora }}</td><td>{{ $i->hc_diagnostico }}</td>
                            <td class="text-center"><a href="{{ url('/pdf/informe/'.$i->id) }}" target="_blank" class="btn btn-sm btn-generar me-1" title="Descargar / imprimir PDF"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</a>
@if(session('rol') === 'Administrador')
<a href="{{ url('/informe/'.$i->id.'/editar') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Editar</a>
<form action="{{ url('/informe/'.$i->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
@endif</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>
</div>

<div class="tab-pane fade" id="panel-filtros" role="tabpanel">
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center"><div class="col-md-11 card-table">
        <span class="section-label"><i class="bi bi-funnel-fill"></i> Búsqueda y filtros</span>
        <div class="row g-3 my-2 p-3 bg-light rounded border">
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Nombre del paciente:</label>
                <input type="text" id="busqueda_paciente" class="form-control" placeholder="Escribe para buscar...">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Diagnóstico:</label>
                <input type="text" id="busqueda_diagnostico" class="form-control" placeholder="Ej: Autismo, TDAH...">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="button" id="btnLimpiarFiltros" class="btn btn-secondary w-100"><i class="bi bi-arrow-clockwise"></i> Limpiar filtros</button>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-hover" id="tabla_filtrable">
                <thead class="table-light"><tr><th>Paciente</th><th>Diagnóstico</th><th class="text-center">PDF</th></tr></thead>
                <tbody>
                @foreach($informes->sortBy('paciente') as $i)
                    <tr>
                        <td class="col-paciente">{{ $i->paciente }}</td>
                        <td class="col-diagnostico">{{ $i->hc_diagnostico }}</td>
                        <td class="text-center"><a href="{{ url('/pdf/informe/'.$i->id) }}" target="_blank" class="btn btn-sm btn-generar"><i class="bi bi-file-earmark-pdf-fill"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div></div>
</div>
</div>

<div class="tab-pane fade" id="panel-graficas" role="tabpanel">
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center"><div class="col-md-11 card-table">
        <span class="section-label"><i class="bi bi-bar-chart-line"></i> Estadísticas y gráficas</span>
        <p class="text-muted">Distribución de los diagnósticos registrados en los informes del Centro DIVM.</p>
        <div class="card shadow-sm border-0 mt-3 mx-auto" style="max-width:750px;">
            <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center" style="background-color:#580c33;">
                <span><i class="bi bi-pie-chart-fill me-2"></i> Prevalencia de diagnósticos</span>
                <button type="button" id="btnRecargarGrafica" class="btn btn-sm btn-outline-light py-0 px-2"><i class="bi bi-arrow-clockwise"></i> Actualizar</button>
            </div>
            <div class="card-body bg-light d-flex justify-content-center align-items-center" style="position:relative;min-height:380px;">
                <canvas id="graficaDiagnosticos"></canvas>
            </div>
            <div class="card-footer text-muted small bg-white text-center">
                <i class="bi bi-info-circle"></i> Los datos se calculan automáticamente con el historial de informes.
            </div>
        </div>
    </div></div>
</div>
</div>

<div class="tab-pane fade" id="panel-plantillas" role="tabpanel">
<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center"><div class="col-md-11 card-table">
        <span class="section-label"><i class="bi bi-journal-bookmark-fill"></i> Banco de guías e indicadores pedagógicos</span>
        <p class="text-muted">Referencias rápidas para homologar las evaluaciones conductuales y de desarrollo:</p>
        <div class="row g-3 mt-2">
            <div class="col-md-4"><div class="card h-100"><div class="card-header fw-bold" style="color:#84063e;">Área cognitiva</div><div class="card-body small"><ul class="mb-0">
                <li>Atención sostenida / focalizada.</li>
                <li>Seguimiento de instrucciones simples y complejas.</li>
                <li>Identificación de formas, colores y seriación.</li>
            </ul></div></div></div>
            <div class="col-md-4"><div class="card h-100"><div class="card-header fw-bold" style="color:#84063e;">Área motriz</div><div class="card-body small"><ul class="mb-0">
                <li><strong>Fina:</strong> agarre de pinza, trazos, uso de tijeras.</li>
                <li><strong>Gruesa:</strong> equilibrio dinámico, saltos, coordinación.</li>
            </ul></div></div></div>
            <div class="col-md-4"><div class="card h-100"><div class="card-header fw-bold" style="color:#84063e;">Área social / conducta</div><div class="card-body small"><ul class="mb-0">
                <li>Tolerancia a la frustración durante la evaluación.</li>
                <li>Interacción ocular y respuestas adaptativas al entorno.</li>
            </ul></div></div></div>
        </div>
    </div></div>
</div>
</div>
    </div>

{{-- ===== ASISTENTE DIVM (chat) ===== --}}
<button id="divm-fab" type="button" title="Asistente DIVM">🎓<span class="divm-badge" id="divm-badge"></span></button>

<div id="divm-ventana">
    <div id="divm-header">
        <div class="divm-avatar-h">🎓</div>
        <div class="divm-info">
            <strong>Asistente DIVM</strong>
            <span><span class="divm-dot"></span>En línea · Pedagógico</span>
        </div>
        <button id="divm-btn-cerrar" type="button" title="Cerrar">✕</button>
    </div>
    <div id="divm-mensajes"></div>
    <div id="divm-input-area">
        <textarea id="divm-input" rows="1" placeholder="Escribe tu consulta..."></textarea>
        <button id="divm-send" type="button" disabled title="Enviar">&#10148;</button>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
/* ---------- Filtros ---------- */
(function () {
    var p = document.getElementById('busqueda_paciente'), d = document.getElementById('busqueda_diagnostico');
    function filtrar() {
        var vp = p.value.toLowerCase(), vd = d.value.toLowerCase();
        document.querySelectorAll('#tabla_filtrable tbody tr').forEach(function (tr) {
            var cp = (tr.querySelector('.col-paciente').textContent || '').toLowerCase();
            var cd = (tr.querySelector('.col-diagnostico').textContent || '').toLowerCase();
            tr.style.display = (cp.indexOf(vp) > -1 && cd.indexOf(vd) > -1) ? '' : 'none';
        });
    }
    p.addEventListener('input', filtrar); d.addEventListener('input', filtrar);
    document.getElementById('btnLimpiarFiltros').addEventListener('click', function () { p.value = ''; d.value = ''; filtrar(); });
})();

/* ---------- Gráfica de diagnósticos ---------- */
(function () {
    var grafica = null;
    function cargar() {
        fetch("{{ url('/informe/grafica') }}", { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (datos) {
                if (grafica) grafica.destroy();
                grafica = new Chart(document.getElementById('graficaDiagnosticos').getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: datos.map(function (x) { return x.diagnostico; }),
                        datasets: [{
                            data: datos.map(function (x) { return parseInt(x.total, 10); }),
                            backgroundColor: ['#c5005a', '#54063e', '#84063e', '#fd7e14', '#0d6efd', '#198754', '#6c757d'],
                            borderWidth: 2, borderColor: '#fff'
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                });
            });
    }
    document.getElementById('graficas-tab').addEventListener('click', function () { setTimeout(cargar, 150); });
    document.getElementById('btnRecargarGrafica').addEventListener('click', cargar);
})();

/* ---------- Asistente DIVM ---------- */
(function () {
    var ventana = document.getElementById('divm-ventana'), msgs = document.getElementById('divm-mensajes'),
        input = document.getElementById('divm-input'), enviar = document.getElementById('divm-send'),
        badge = document.getElementById('divm-badge'), historial = [], cargando = false, abierta = false;
    var csrf = document.querySelector('meta[name="csrf-token"]').content;

    function esc(t) { return String(t).replace(/[&<>"']/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
    function formato(t) { return esc(t).replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>'); }

    function agregar(rol, html) {
        var fila = document.createElement('div'); fila.className = 'divm-fila' + (rol === 'usuario' ? ' usuario' : '');
        var av = document.createElement('div'); av.className = 'divm-avatar ' + (rol === 'bot' ? 'bot' : 'user'); av.textContent = rol === 'bot' ? '🎓' : '👤';
        var bu = document.createElement('div'); bu.className = 'divm-burbuja ' + (rol === 'bot' ? 'bot' : 'usuario'); bu.innerHTML = html;
        fila.appendChild(av); fila.appendChild(bu); msgs.appendChild(fila); msgs.scrollTop = msgs.scrollHeight; return fila;
    }
    function typing() {
        var f = document.createElement('div'); f.className = 'divm-fila';
        f.innerHTML = '<div class="divm-avatar bot">🎓</div><div class="divm-burbuja bot"><div class="divm-typing"><span></span><span></span><span></span></div></div>';
        msgs.appendChild(f); msgs.scrollTop = msgs.scrollHeight; return f;
    }
    function alternar() {
        abierta = !abierta; ventana.classList.toggle('abierto', abierta);
        if (abierta) { badge.style.display = 'none'; setTimeout(function () { input.focus(); }, 120); }
    }
    function mandar(texto) {
        texto = (texto || '').trim(); if (!texto || cargando) return;
        input.value = ''; input.style.height = 'auto'; enviar.disabled = true;
        agregar('usuario', esc(texto)); historial.push({ role: 'user', content: texto });
        cargando = true; var t = typing();
        fetch("{{ url('/chatbot') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ messages: historial })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            t.remove();
            if (data.error) { agregar('bot', '⚠️ ' + esc(data.error)); return; }
            historial.push({ role: 'assistant', content: data.respuesta });
            agregar('bot', formato(data.respuesta));
            if (!abierta) badge.style.display = 'block';
        })
        .catch(function () { t.remove(); agregar('bot', '⚠️ No se pudo conectar con el asistente.'); })
        .finally(function () { cargando = false; enviar.disabled = !input.value.trim(); });
    }

    document.getElementById('divm-fab').addEventListener('click', alternar);
    document.getElementById('divm-btn-cerrar').addEventListener('click', alternar);
    enviar.addEventListener('click', function () { mandar(input.value); });
    input.addEventListener('keydown', function (e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); mandar(input.value); } });
    input.addEventListener('input', function () { enviar.disabled = !input.value.trim() || cargando; this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 88) + 'px'; });

    agregar('bot', '¡Hola! Soy el asistente del <strong>Centro DIVM</strong> 🎓<br>Puedo orientarte sobre informes, diagnósticos y el uso del sistema. ¿En qué te ayudo?');
    var sug = document.createElement('div'); sug.id = 'divm-sugerencias'; sug.innerHTML = '<p>Preguntas frecuentes:</p>';
    ['¿Cómo registro un nuevo informe pedagógico?', '¿Qué evalúa el área cognitiva?', '¿Cómo genero el PDF de un informe?'].forEach(function (q) {
        var b = document.createElement('button'); b.type = 'button'; b.className = 'divm-sug-btn'; b.textContent = '💬 ' + q;
        b.onclick = function () { sug.remove(); mandar(q); }; sug.appendChild(b);
    });
    msgs.appendChild(sug);
})();
</script>
@endpush
