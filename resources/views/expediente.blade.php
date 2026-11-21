@extends('layouts.app')

@section('title', 'Expediente - DIVM')
@section('modulo', 'Expediente del paciente')

@section('content')
<div class="container-fluid px-0">

    <div class="card-table mb-4">
        <span class="section-label"><i class="bi bi-folder2-open me-2"></i>Expediente</span>
        <form method="GET" onsubmit="var s=this.querySelector('select').value; if(!s){return false;} window.location.href='{{ url('/expediente') }}/'+encodeURIComponent(s); return false;">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill me-2"></i>Paciente:</span>
                <select class="form-select" onchange="if(this.value){window.location.href='{{ url('/expediente') }}/'+encodeURIComponent(this.value);}">
                    <option value="">Seleccionar Paciente...</option>
                    @foreach($lista as $p)
                        <option value="{{ $p->nombre }}" {{ $paciente && $paciente->id === $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    @if($paciente)
        <div class="card-table mb-4">
            <span class="section-label"><i class="bi bi-person-vcard-fill me-2"></i>{{ $paciente->nombre }}</span>
            <div class="row g-2">
                @foreach(['Edad' => $paciente->edad, 'Nacimiento' => $paciente->fecha_nacimiento, 'Diagnóstico' => $paciente->diagnostico,
                          'Estatus' => $paciente->estatus, 'Madre' => $paciente->madre, 'Padre' => $paciente->padre,
                          'Domicilio' => $paciente->direccion, 'Motivo de consulta' => $paciente->motivo_consulta] as $k => $v)
                    <div class="col-md-6"><strong>{{ $k }}:</strong> {{ $v !== null && $v !== '' ? $v : '—' }}</div>
                @endforeach
            </div>
        </div>

        @foreach($secciones as $titulo => $registros)
            <div class="card-table mb-3">
                <span class="section-label">{{ $titulo }} <span class="badge bg-secondary ms-1">{{ $registros->count() }}</span></span>
                @forelse($registros as $r)
                    <details class="expediente-item">
                        <summary>Registro #{{ $r->getKey() }}
                            @php $f = $r->fecha_registro ?? $r->created_at ?? $r->fecha ?? null; @endphp
                            @if($f) <small class="text-muted">· {{ $f }}</small>@endif
                        </summary>
                        <dl class="row mb-0 mt-2">
                            @foreach($r->getAttributes() as $campo => $valor)
                                @continue($valor === null || $valor === '' || in_array($campo, ['password', 'id', 'id_paciente', 'paciente_id']))
                                <dt class="col-sm-4">{{ ucfirst(str_replace('_', ' ', $campo)) }}</dt>
                                <dd class="col-sm-8">{{ $valor }}</dd>
                            @endforeach
                        </dl>
                    </details>
                @empty
                    <p class="text-muted mb-0">Sin registros.</p>
                @endforelse
            </div>
        @endforeach
    @else
        <p class="text-muted">Elige un paciente para ver todo su historial en un solo lugar.</p>
    @endif

</div>
@endsection
