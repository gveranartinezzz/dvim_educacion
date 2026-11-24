@extends('layouts.app')

@section('title', 'Citas - DIVM')
@section('modulo', 'Citas')

@section('content')
@php
    $mesAnt = $ini->copy()->subMonth()->format('Y-m');
    $mesSig = $ini->copy()->addMonth()->format('Y-m');
    $meses  = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $desfase = $ini->dayOfWeekIso - 1;            // lunes = 0
    $dias   = $ini->daysInMonth;
    $hoy    = now()->toDateString();
    $color  = ['Programada' => 'primary', 'Realizada' => 'success', 'Cancelada' => 'secondary', 'No asistió' => 'danger'];
    $valor  = fn ($campo, $def = '') => old($campo, $editar->{$campo} ?? $def);
    $hm     = fn ($h) => substr((string) $h, 0, 5);
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-table">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a class="btn btn-sm btn-outline-secondary" href="{{ url('/citas?mes=' . $mesAnt) }}"><i class="bi bi-chevron-left"></i> Anterior</a>
                <span class="section-label m-0"><i class="bi bi-calendar3 me-2"></i>{{ ucfirst($meses[$ini->month - 1]) }} {{ $ini->year }}</span>
                <a class="btn btn-sm btn-outline-secondary" href="{{ url('/citas?mes=' . $mesSig) }}">Siguiente <i class="bi bi-chevron-right"></i></a>
            </div>

            <div class="cal-grid">
                @foreach(['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $n)<div class="cal-head">{{ $n }}</div>@endforeach
                @for($i = 0; $i < $desfase; $i++)<div class="cal-dia vacio"></div>@endfor
                @for($d = 1; $d <= $dias; $d++)
                    @php
                        $f = $ini->copy()->day($d)->toDateString();
                        $lista = $citasPorDia[$f] ?? collect();
                    @endphp
                    <a class="cal-dia {{ $f === $hoy ? 'hoy' : '' }} {{ $diaSel === $f ? 'sel' : '' }}"
                       href="{{ url('/citas?mes=' . $ini->format('Y-m') . '&dia=' . $f) }}#nueva">
                        <span class="num">{{ $d }}</span>
                        @foreach($lista->take(3) as $c)
                            <span class="cal-cita bg-{{ $color[$c->estatus] ?? 'primary' }}">{{ $hm($c->hora_inicio) }} {{ \Illuminate\Support\Str::limit($c->paciente, 12) }}</span>
                        @endforeach
                        @if($lista->count() > 3)<span class="cal-mas">+{{ $lista->count() - 3 }} más</span>@endif
                    </a>
                @endfor
            </div>
            <p class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Toca un día para agendar una cita en esa fecha.</p>
        </div>

        <div class="card-table mt-4">
            <span class="section-label"><i class="bi bi-list-check me-2"></i>Citas de {{ $meses[$ini->month - 1] }}</span>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>Fecha</th><th>Hora</th><th>Paciente</th><th>Terapeuta</th><th>Motivo</th><th>Estado</th><th></th></tr></thead>
                    <tbody>
                    @forelse($citasPorDia->flatten() as $c)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $hm($c->hora_inicio) }} – {{ $hm($c->hora_fin) }}</td>
                            <td>{{ $c->paciente }}</td>
                            <td>{{ $c->terapeuta ?: '—' }}</td>
                            <td>{{ $c->motivo }}</td>
                            <td><span class="badge bg-{{ $color[$c->estatus] ?? 'primary' }}">{{ $c->estatus }}</span></td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="{{ url('/citas?mes=' . $ini->format('Y-m') . '&editar=' . $c->id) }}#nueva"><i class="bi bi-pencil"></i></a>
                                <form class="d-inline" method="POST" action="{{ url('/citas/' . $c->id) }}">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No hay citas este mes.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-table" id="nueva">
            <span class="section-label"><i class="bi bi-calendar-plus me-2"></i>{{ $editar ? 'Editar cita' : 'Nueva cita' }}</span>
            <form method="POST" action="{{ $editar ? url('/citas/' . $editar->id) : url('/citas') }}">
                @csrf
                @if($editar) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Paciente</label>
                    <select name="paciente" class="form-select" required>
                        <option value="" disabled {{ $valor('paciente') === '' ? 'selected' : '' }}>Seleccionar paciente...</option>
                        @foreach(($listaPacientes ?? []) as $p)
                            @if(($p->estatus ?? 'Activo') !== 'Inactivo' || $valor('paciente') === $p->nombre)
                                <option value="{{ $p->nombre }}" {{ $valor('paciente') === $p->nombre ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Terapeuta</label>
                    <select name="id_usuario" class="form-select" required>
                        <option value="" disabled {{ $valor('id_usuario') === '' ? 'selected' : '' }}>Seleccionar terapeuta...</option>
                        @foreach($terapeutas as $t)
                            <option value="{{ $t->id_usuario }}" {{ (string) $valor('id_usuario') === (string) $t->id_usuario ? 'selected' : '' }}>{{ trim($t->nombre . ' ' . $t->apaterno) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Fecha</label>
                    <input type="date" name="fecha" class="form-control" required value="{{ old('fecha', $editar->fecha ?? $diaSel ?? '') }}">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold text-secondary small">Hora de inicio</label>
                        <input type="time" name="hora_inicio" class="form-control" required value="{{ old('hora_inicio', $hm($editar->hora_inicio ?? '')) }}">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold text-secondary small">Hora de fin</label>
                        <input type="time" name="hora_fin" class="form-control" required value="{{ old('hora_fin', $hm($editar->hora_fin ?? '')) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Motivo de la cita</label>
                    <input type="text" name="motivo" class="form-control" required value="{{ $valor('motivo') }}">
                </div>
                @if($editar)
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Estado de la cita</label>
                    <select name="estatus" class="form-select">
                        @foreach($estatus as $e)<option value="{{ $e }}" {{ $valor('estatus', 'Programada') === $e ? 'selected' : '' }}>{{ $e }}</option>@endforeach
                    </select>
                </div>
                @endif
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small">Notas</label>
                    <textarea name="notas" rows="3" class="form-control">{{ $valor('notas') }}</textarea>
                </div>
                <button class="btn btn-divm w-100" type="submit"><i class="bi bi-check2-circle me-1"></i>{{ $editar ? 'Guardar cambios' : 'Agendar cita' }}</button>
                @if($editar)<a class="btn btn-link w-100" href="{{ url('/citas?mes=' . $ini->format('Y-m')) }}">Cancelar edición</a>@endif
            </form>
        </div>

        <div class="card-table mt-4">
            <span class="section-label"><i class="bi bi-alarm me-2"></i>Próximas citas</span>
            @forelse($proximas as $c)
                <div class="border-bottom py-2 small">
                    <strong>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m') }} {{ $hm($c->hora_inicio) }}</strong> · {{ $c->paciente }}
                    <div class="text-muted">{{ $c->motivo }}</div>
                </div>
            @empty
                <p class="text-muted small mb-0">No hay citas programadas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
