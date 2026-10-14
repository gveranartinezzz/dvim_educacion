@extends('layouts.app')


@section('content')
<div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 bg-white p-4 rounded shadow-sm">
                <div class="titulo-banner">
                    <h3 class="mb-0">Modificar Recomendación Escolar</h3>
                </div>

                <form action="{{ url('/recomendaciones/'.$rec->id) }}" method="POST">
@csrf
@method('PUT')
                    <input type="hidden" name="id" value="{{ $rec->id }}">
                    
                    <span class="section-label">Identificación del Alumno</span>
                    <div class="input-group mb-2">
                        <span class="input-group-text">Paciente:</span>
                        <input type="text" name="paciente" class="form-control" value="{{ $rec->paciente }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6"><div class="input-group"><span class="input-group-text">Madre:</span><input type="text" name="madre" class="form-control" value="{{ $rec->madre }}" required></div></div>
                        <div class="col-md-6"><div class="input-group"><span class="input-group-text">Padre:</span><input type="text" name="padre" class="form-control" value="{{ $rec->padre }}" required></div></div>
                    </div>

                    <span class="section-label mt-4">Información Académica</span>
                    <div class="row mb-4">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text">Escuela:</span>
                                <input type="text" name="escuela_procedencia" class="form-control" value="{{ $rec->escuela_procedencia }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text">Grado:</span>
                                <select name="grado" class="form-select">
                                    @foreach(["Estancia","Preescolar","Primaria","Secundaria","Preparatoria","Universidad"] as $g)<option value="{{ $g }}" {{ $rec->grado == $g ? 'selected' : '' }}>{{ $g }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <span class="section-label">Detalles de la Sugerencia</span>
                    <div class="mb-3">
                        <label class="area-label">Recomendación Pedagógica</label>
                        <textarea name="recomendacion" class="form-control" rows="4" required>{{ $rec->recomendacion }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="area-label">Notas Internas</label>
                        <textarea name="notas" class="form-control" rows="2">{{ $rec->notas }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ url('/recomendaciones') }}" class="btn btn-secondary px-5">CANCELAR</a>
                        <button type="submit" class="btn btn-update px-5 shadow-sm">ACTUALIZAR REGISTRO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
