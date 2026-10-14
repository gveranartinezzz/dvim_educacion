{{-- Botón del menú lateral que abre un panel de la página.
     Uso: @include('layouts.tab', ['id' => 'nuevo', 'icono' => 'bi-plus', 'texto' => 'Nuevo', 'activo' => true])
     Abre el panel con id="panel-{id}" --}}
<li class="nav-item mb-1">
    <button type="button"
            class="nav-link w-100 text-start bg-transparent border-0 side-tab-btn {{ !empty($activo) ? 'active' : '' }}"
            id="{{ $id }}-tab"
            data-bs-toggle="tab"
            data-bs-target="#panel-{{ $id }}"
            role="tab"
            aria-controls="panel-{{ $id }}"
            aria-selected="{{ !empty($activo) ? 'true' : 'false' }}">
        <i class="bi {{ $icono ?? 'bi-dot' }} me-2"></i>{{ $texto }}
    </button>
</li>
