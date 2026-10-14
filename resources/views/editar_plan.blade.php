@extends('layouts.app')


@section('content')
<div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-9 bg-white p-4 rounded shadow-sm">
                
                <div class="titulo-banner">
                    <h3 class="mb-0">Modificar Plan de Intervención</h3>
                </div>

                <form action="{{ url('/plan/'.$plan->id) }}" method="POST">
@csrf
@method('PUT')
                    <input type="hidden" name="id" value="{{ $plan->id }}">
                    
                    <span class="section-label">Identificación del Paciente</span>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text">Paciente:</span>
                        <input type="text" name="paciente" class="form-control" value="{{ $plan->paciente }}" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Padre:</span>
                                <input type="text" name="padre" class="form-control" value="{{ $plan->padre }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Madre:</span>
                                <input type="text" name="madre" class="form-control" value="{{ $plan->madre }}" required>
                            </div>
                        </div>
                    </div>

                    <span class="section-label mt-4">Áreas de Intervención Especializada</span>

                    <div class="mb-3">
                        <label class="area-label">Terapia Comunicativa</label>
                        <textarea name="terapia_comunicativa" class="form-control" rows="2">{{ $plan->terapia_comunicativa }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="area-label">Terapia de Integración Sensorial</label>
                        <textarea name="terapia_sensorial" class="form-control" rows="2">{{ $plan->terapia_sensorial }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="area-label">Terapia de Aprendizaje</label>
                        <textarea name="terapia_aprendizaje" class="form-control" rows="2">{{ $plan->terapia_aprendizaje }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="area-label">Terapia Ocupacional</label>
                        <textarea name="terapia_ocupacional" class="form-control" rows="2">{{ $plan->terapia_ocupacional }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="area-label">Terapia de Atención y Concentración</label>
                        <textarea name="terapia_atencion_concentracion" class="form-control" rows="2">{{ $plan->terapia_atencion_concentracion }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="area-label">Terapia de Educación Emocional</label>
                        <textarea name="terapia_educacion_emocional" class="form-control" rows="2">{{ $plan->terapia_educacion_emocional }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <a href="{{ url('/plan') }}" class="btn btn-secondary px-4">Cancelar</a>
                        <button type="submit" class="btn btn-update px-5 shadow-sm">Actualizar Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
