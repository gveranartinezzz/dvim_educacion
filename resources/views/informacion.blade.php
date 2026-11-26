@extends('layouts.app')

@section('title', 'Inicio - DIVM')

@section('content')
@php
    $esAdmin  = session('rol') === 'Administrador';
    $dom      = preg_replace('/\s+/', ' ', (string) $c['domicilio']);
    $mapaUrl  = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($dom);
    $mapaEmb  = 'https://maps.google.com/maps?q=' . urlencode($dom) . '&hl=es&z=16&output=embed';
@endphp

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card-divm mb-4">

                <div class="header-divm text-center py-4 mb-4">
                    <h1 class="display-5 fw-bold">{{ $c['nombre_centro'] }}</h1>
                    <h4 class="mb-0">{{ $c['subtitulo'] }}</h4>
                </div>

                @if($esAdmin)
                    <div class="text-end mb-3">
                        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#editarInicio" aria-expanded="{{ $errors->any() ? 'true' : 'false' }}">
                            <i class="bi bi-pencil-square me-1"></i> Editar información del centro
                        </button>
                    </div>

                    <div class="collapse {{ $errors->any() ? 'show' : '' }} mb-4" id="editarInicio">
                        <form method="POST" action="{{ route('inicio.guardar') }}" class="border rounded p-3 bg-light">
                            @csrf
                            <span class="section-label"><i class="bi bi-pencil-square me-2"></i>Editar información del centro</span>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary small">Nombre del centro</label>
                                    <input name="nombre_centro" class="form-control" maxlength="80" required value="{{ old('nombre_centro', $c['nombre_centro']) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary small">Subtítulo (tipo de escuela y ciudad)</label>
                                    <input name="subtitulo" class="form-control" maxlength="150" required value="{{ old('subtitulo', $c['subtitulo']) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary small">Cargo</label>
                                    <input name="cargo" class="form-control" maxlength="80" required value="{{ old('cargo', $c['cargo']) }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-secondary small">Nombre de la directora o educadora</label>
                                    <input name="educadora" class="form-control" maxlength="120" minlength="3" required data-tipo="nombre" value="{{ old('educadora', $c['educadora']) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-secondary small">Descripción</label>
                                    <textarea name="descripcion" rows="2" maxlength="400" class="form-control">{{ old('descripcion', $c['descripcion']) }}</textarea>
                                    <div class="form-text">Máximo 400 caracteres.</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-secondary small">Domicilio</label>
                                    <textarea name="domicilio" rows="2" maxlength="250" minlength="5" required class="form-control">{{ old('domicilio', $c['domicilio']) }}</textarea>
                                    <div class="form-text">El mapa se actualiza solo con este domicilio.</div>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold text-secondary small">Teléfono (opcional)</label>
                                    <input name="telefono" class="form-control" maxlength="20" inputmode="tel" data-tipo="telefono" value="{{ old('telefono', $c['telefono']) }}">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label fw-bold text-secondary small">Horario de atención (opcional)</label>
                                    <input name="horario" class="form-control" maxlength="120" placeholder="Ej. Lunes a viernes de 8:00 a 14:00" value="{{ old('horario', $c['horario']) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-secondary small">Mensaje de bienvenida</label>
                                    <input name="bienvenida" class="form-control" maxlength="150" required value="{{ old('bienvenida', $c['bienvenida']) }}">
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-2">
                                <button type="submit" class="btn btn-divm"><i class="bi bi-check2-circle me-1"></i> Guardar cambios</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#editarInicio">Cancelar</button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <span class="section-label"><i class="bi bi-person-badge-fill"></i> {{ $c['cargo'] }}</span>
                        <p class="fs-5 fw-bold text-divm">{{ $c['educadora'] }}</p>
                        @if(trim($c['descripcion']) !== '')<p>{{ $c['descripcion'] }}</p>@endif

                        <span class="section-label"><i class="bi bi-geo-alt-fill"></i> Ubicación</span>
                        <p>
                            <strong>Domicilio:</strong><br>
                            {!! nl2br(e($c['domicilio'])) !!}
                        </p>
                        @if(trim($c['telefono']) !== '')<p class="mb-1"><i class="bi bi-telephone-fill me-1"></i> {{ $c['telefono'] }}</p>@endif
                        @if(trim($c['horario']) !== '')<p class="mb-2"><i class="bi bi-clock-fill me-1"></i> {{ $c['horario'] }}</p>@endif

                        <a href="{{ $mapaUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm mb-4">
                            <i class="bi bi-map"></i> Ver en Google Maps
                        </a>
                    </div>

                    <div class="col-md-6">
                        <span class="section-label"><i class="bi bi-map-fill"></i> Ubicación en el Mapa</span>
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $mapaEmb }}" style="border:0; border-radius:10px;" allowfullscreen="" loading="lazy" title="Mapa del centro"></iframe>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded" style="background-color: #fceaf6;">
                    <p class="mb-0 text-center fw-bold text-divm">
                        <i class="bi bi-star-fill"></i> {{ $c['bienvenida'] }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
