{{-- Lista de pacientes registrados. Vars: $pacientes, $actual (opcional: nombre ya guardado), $name (opcional) --}}
@php
    $actual = $actual ?? old($name ?? 'paciente');
    $campo  = $name ?? 'paciente';
    $nombres = $pacientes->pluck('nombre')->all();
@endphp
<select name="{{ $campo }}" class="form-select select-paciente" required>
    <option value="" disabled {{ $actual ? '' : 'selected' }}>Seleccionar paciente...</option>
    @if($actual && !in_array($actual, $nombres, true))
        <option value="{{ $actual }}" selected>{{ $actual }} (no registrado)</option>
    @endif
    @foreach($pacientes as $p)
        <option value="{{ $p->nombre }}"
                data-padre="{{ $p->padre }}" data-madre="{{ $p->madre }}"
                data-nacimiento="{{ $p->fecha_nacimiento ? \Illuminate\Support\Carbon::parse($p->fecha_nacimiento)->format('Y-m-d') : '' }}"
                data-direccion="{{ $p->direccion }}"
                {{ $actual === $p->nombre ? 'selected' : '' }}>{{ $p->nombre }}</option>
    @endforeach
</select>
<script>
(function () {
    var s = document.currentScript.previousElementSibling;
    var mapa = {padre:['padre','nombre_padre'], madre:['madre','nombre_madre'], nacimiento:['fecha_nacimiento'], direccion:['domicilio']};
    s.addEventListener('change', function () {
        var o = s.options[s.selectedIndex], f = s.form;
        if (!o || !f) return;
        Object.keys(mapa).forEach(function (k) {
            var v = o.dataset[k];
            if (v === undefined || v === '') return;
            mapa[k].forEach(function (n) { if (f.elements[n]) f.elements[n].value = v; });
        });
    });
})();
</script>
