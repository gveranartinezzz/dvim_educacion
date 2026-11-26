@php
    $esAdmin = session('rol') === 'Administrador';
    $modulos = [
        ['/inicio',          'Inicio',          'bi-house-door-fill',        'inicio*',          false],
        ['/panel',           'Panel',           'bi-speedometer2',           'panel*',           false],
        ['/registro',        'Pacientes',       'bi-people-fill',            'registro*',        false],
        ['/plan',            'Planes',          'bi-journal-text',           'plan*',            false],
        ['/recomendaciones', 'Recomendaciones', 'bi-lightbulb-fill',         'recomendaciones*', false],
        ['/informe',         'Informes',        'bi-file-earmark-text-fill', 'informe*',         false],
        ['/valoracion',      'Valoración',      'bi-clipboard2-pulse-fill',  'valoracion*',      false],
        ['/avances',         'Avances',         'bi-graph-up-arrow',         'avances*',         false],
        ['/distribucion',    'Distribución',    'bi-calendar-week-fill',     'distribucion*',    false],
        ['/expediente',      'Expediente',      'bi-folder2-open',           'expediente*',      false],
        ['/citas',           'Citas',           'bi-calendar-check',         'citas*',           false],
        ['/usuarios',        'Usuarios',        'bi-person-gear',            'usuarios*',        true],
        ['/bitacora',        'Bitácora',        'bi-clock-history',          'bitacora*',        true],
    ];
@endphp

<!-- =========================
     SIDEBAR
========================= -->
<nav id="sidebar">

    <ul class="list-unstyled components nav flex-column" id="dynamicTabsList">
        <li>

            <!-- TÍTULO DEL MÓDULO (cada página lo define con @section('modulo')) -->
            <a href="#dynamicSubmenu"
               data-bs-toggle="collapse"
               role="button"
               aria-expanded="true"
               aria-controls="dynamicSubmenu"
               class="dropdown-toggle text-white d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-grid-1x2-fill me-2"></i>
                    @yield('modulo', 'Menú Principal')
                </span>
            </a>

            <!-- BOTONES: abren los paneles de la página -->
            <ul class="collapse show list-unstyled ps-3 mt-2 nav flex-column" id="dynamicSubmenu" role="tablist">
                @hasSection('sidebar_tabs')
                    @yield('sidebar_tabs')
                @else
                    {{-- Páginas sin paneles: enlaces a los módulos --}}
                    @foreach($modulos as [$ruta, $texto, $icono, $patron, $soloAdmin])
                        @continue($soloAdmin && !$esAdmin)
                        <li class="nav-item mb-1">
                            <a href="{{ url($ruta) }}" class="nav-link side-tab-btn {{ request()->is($patron) ? 'active' : '' }}">
                                <i class="bi {{ $icono }} me-2"></i>{{ $texto }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>

        </li>
    </ul>

    <!-- PIE DEL SIDEBAR -->
    <div class="sidebar-footer text-center p-3">
        <i class="bi bi-person-circle fs-2 text-white-50"></i>
        <p class="mb-0 small text-white fw-bold">{{ session('nombre', 'Invitado') }}</p>
        <p class="mb-1 small text-white-50 text-uppercase">{{ session('rol', 'Invitado') }}</p>
        <form method="POST" action="{{ url('/logout') }}">@csrf<button type="submit" class="btn btn-sm btn-danger w-100 mt-2 btn-sidebar-logout">Cerrar Sesión</button></form>
    </div>

</nav>
