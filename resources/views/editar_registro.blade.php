@extends('layouts.app')


@section('content')
<div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-9 bg-white p-4 rounded shadow-sm">
                <div class="titulo-banner">
                    <h3 class="mb-0">Modificar Expediente del Paciente</h3>
                </div>

                <form action="{{ url('/registro/'.$paciente->id) }}" method="POST">
@csrf
@method('PUT')
                    <input type="hidden" name="id" value="{{ $paciente->id }}">

                    <span class="section-label">Datos Generales</span>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Nombre:</span>
                        <input type="text" name="nombre" class="form-control" value="{{ $paciente->nombre }}" required>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Fecha Nac.:</span>
                                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $paciente->fecha_nacimiento }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Edad:</span>
                                <input type="number" name="edad" class="form-control" value="{{ $paciente->edad }}" required min="0">
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <span class="input-group-text">Dirección:</span>
                        <textarea name="direccion" class="form-control" rows="1">{{ $paciente->direccion }}</textarea>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Escolarizado:</span>
                                <select name="escolarizado" class="form-select">
                                    <option value="No" {{ $paciente->escolarizado == 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Sí" {{ $paciente->escolarizado == 'Sí' ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Diagnóstico:</span>
                                <input type="text" name="diagnostico" class="form-control" value="{{ $paciente->diagnostico }}">
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-4">
                        <span class="input-group-text">Motivo Consulta:</span>
                        <textarea name="motivo_consulta" class="form-control" rows="2">{{ $paciente->motivo_consulta }}</textarea>
                    </div>

                    <span class="section-label mt-4">Información de los Padres</span>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Madre:</span>
                        <input type="text" name="madre" class="form-control" value="{{ $paciente->madre }}">
                    </div>
                    <div class="input-group mb-4">
                        <span class="input-group-text">Padre:</span>
                        <input type="text" name="padre" class="form-control" value="{{ $paciente->padre }}">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ url('/registro') }}" class="btn btn-secondary px-4">Cancelar</a>
                        <button type="submit" class="btn btn-update px-5 shadow-sm">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection
