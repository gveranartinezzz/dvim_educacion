@extends('layouts.app')


@section('content')
<div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10 bg-white p-4 rounded shadow-sm">
                
                <div class="titulo-banner">
                    <h3 class="mb-0">Modificar Informe Pedagógico</h3>
                </div>

                <form action="{{ url('/informe/'.$inf->id) }}" method="POST">
@csrf
@method('PUT')
                    <input type="hidden" name="id" value="{{ $inf->id }}">
                    
                    <span class="section-label">Datos de Identificación</span>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text">Paciente:</span>
                        <input type="text" name="paciente" class="form-control" value="{{ $inf->paciente }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <span class="area-label">Datos del Padre</span>
                            <input type="text" name="nombre_padre" class="form-control mb-2" value="{{ $inf->nombre_padre }}">
                            <div class="row g-2">
                                <div class="col-8"><input type="text" name="grado_estudios_padre" class="form-control" value="{{ $inf->grado_estudios_padre }}"></div>
                                <div class="col-4"><input type="number" name="edad_padre" class="form-control" value="{{ $inf->edad_padre }}"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <span class="area-label">Datos de la Madre</span>
                            <input type="text" name="nombre_madre" class="form-control mb-2" value="{{ $inf->nombre_madre }}">
                            <div class="row g-2">
                                <div class="col-8"><input type="text" name="grado_estudios_madre" class="form-control" value="{{ $inf->grado_estudios_madre }}"></div>
                                <div class="col-4"><input type="number" name="edad_madre" class="form-control" value="{{ $inf->edad_madre }}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text" style="width: 120px;">F. Nacimiento:</span>
                                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $inf->fecha_nacimiento }}">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text" style="width: 120px;">Domicilio:</span>
                                <input type="text" name="domicilio" class="form-control" value="{{ $inf->domicilio }}">
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Evaluación y Motivo</span>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="area-label">Prueba Aplicada</label>
                            <textarea name="prueba_aplicada" class="form-control" rows="2">{{ $inf->prueba_aplicada }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="area-label">Aplicadora</label>
                            <textarea name="aplicadora" class="form-control" rows="2">{{ $inf->aplicadora }}</textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="area-label">Motivo de la Evaluación</label>
                        <textarea name="motivo_evaluacion" class="form-control" rows="2">{{ $inf->motivo_evaluacion }}</textarea>
                    </div>

                    <span class="section-label">Historia Clínica y Resultados</span>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="fw-bold small">Gestación:</label><input type="text" name="hc_gestacion" class="form-control" value="{{ $inf->hc_gestacion }}"></div>
                        <div class="col-md-4"><label class="fw-bold small">Talla/Peso:</label><input type="text" name="hc_talla_nacer" class="form-control" value="{{ $inf->hc_talla_nacer }}"></div>
                        <div class="col-md-4"><label class="fw-bold small">Diagnóstico:</label><input type="text" name="hc_diagnostico" class="form-control" value="{{ $inf->hc_diagnostico }}"></div>
                        
                        <div class="col-md-6"><label class="area-label">Lenguaje y Comunicación</label><textarea name="res_lenguaje" class="form-control" rows="2">{{ $inf->res_lenguaje }}</textarea></div>
                        <div class="col-md-6"><label class="area-label">Conducta en Valoración</label><textarea name="res_conducta_valoracion" class="form-control" rows="2">{{ $inf->res_conducta_valoracion }}</textarea></div>
                    </div>

                    <span class="section-label">Desarrollo y Áreas</span>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="area-label">Cognitivo</label><textarea name="des_cognitivo" class="form-control" rows="3">{{ $inf->des_cognitivo }}</textarea></div>
                        <div class="col-md-4"><label class="area-label">Motor</label><textarea name="des_motor" class="form-control" rows="3">{{ $inf->des_motor }}</textarea></div>
                        <div class="col-md-4"><label class="area-label">Social</label><textarea name="des_social" class="form-control" rows="3">{{ $inf->des_social }}</textarea></div>
                    </div>

                    <span class="section-label">Indicaciones Finales</span>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="fw-bold small text-muted">CLÍNICAS / FAMILIARES:</label>
                            <textarea name="ind_clinicas" class="form-control mb-2" rows="2">{{ $inf->ind_clinicas }}</textarea>
                            <textarea name="ind_familiares" class="form-control" rows="2">{{ $inf->ind_familiares }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small text-muted">ESCOLARIDAD ACTUAL:</label>
                            <select name="ind_escolares" class="form-select mb-3">
                                <option value="Estancia" {{ $inf->ind_escolares == 'Estancia' ? 'selected' : '' }}>Estancia</option>
                                <option value="Preescolar" {{ $inf->ind_escolares == 'Preescolar' ? 'selected' : '' }}>Preescolar</option>
                                <option value="Primaria" {{ $inf->ind_escolares == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                <option value="Secundaria" {{ $inf->ind_escolares == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                                <option value="Preparatoria" {{ $inf->ind_escolares == 'Preparatoria' ? 'selected' : '' }}>Preparatoria</option>
                                <option value="Universidad" {{ $inf->ind_escolares == 'Universidad' ? 'selected' : '' }}>Universidad</option>
                            </select>
                            <label class="fw-bold small text-muted">REHABILITADORES:</label>
                            <textarea name="ind_rehabilitadores" class="form-control" rows="2">{{ $inf->ind_rehabilitadores }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <a href="{{ url('/informe') }}" class="btn btn-secondary px-4">Cancelar</a>
                        <button type="submit" class="btn btn-update px-5 shadow-sm">Actualizar Informe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
