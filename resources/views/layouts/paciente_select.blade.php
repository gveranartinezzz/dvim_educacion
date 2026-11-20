{{-- Selector de paciente (el mismo en todos los formularios).
     El paciente se escribe UNA sola vez, en el módulo Pacientes; aquí solo se elige de la lista.
     Variables (todas opcionales):
       $campo     nombre del campo que se envía            (por defecto 'paciente')
       $valor     paciente actual al editar un registro
       $etiqueta  texto de la etiqueta                      (por defecto 'Paciente:')
       $clase     clase de margen del grupo (puede ir vacía) (por defecto 'mb-3')
     Los pacientes llegan en $listaPacientes (AppServiceProvider). --}}
@php
    $pcCampo    = $campo ?? 'paciente';
    $pcActual   = (string) old($pcCampo, $valor ?? '');
    $pcLista    = collect($listaPacientes ?? [])->filter(fn ($p) => ($p->estatus ?? 'Activo') !== 'Inactivo');
    $pcEnLista  = $pcLista->contains(fn ($p) => $p->nombre === $pcActual);
    $pcFecha    = fn ($f) => preg_match('/^\d{4}-\d{2}-\d{2}/', (string) $f) ? substr((string) $f, 0, 10) : '';
@endphp
<div class="input-group {{ $clase ?? 'mb-3' }}">

    <span class="input-group-text">
        <i class="bi bi-person-fill me-2"></i>
        {{ $etiqueta ?? 'Paciente:' }}
    </span>

    <select name="{{ $pcCampo }}" class="form-select" required data-paciente-select>
        <option value="" disabled {{ $pcActual === '' ? 'selected' : '' }}>Seleccionar Paciente...</option>
        @if($pcActual !== '' && !$pcEnLista)
            {{-- Registro anterior cuyo paciente ya no está activo: se conserva para no perder el dato --}}
            <option value="{{ $pcActual }}" selected>{{ $pcActual }}</option>
        @endif
        @foreach($pcLista as $p)
            <option value="{{ $p->nombre }}"
                    {{ $pcActual === $p->nombre ? 'selected' : '' }}
                    data-madre="{{ $p->madre }}"
                    data-padre="{{ $p->padre }}"
                    data-nacimiento="{{ $pcFecha($p->fecha_nacimiento) }}"
                    data-domicilio="{{ $p->direccion }}">{{ $p->nombre }}</option>
        @endforeach
    </select>

</div>
@if($pcLista->isEmpty())
    <div class="alert alert-warning py-2 small">
        <i class="bi bi-info-circle-fill me-1"></i>
        Todavía no hay pacientes registrados. Primero regístralo en
        <a href="{{ url('/registro') }}" class="alert-link">Pacientes</a>.
    </div>
@endif
