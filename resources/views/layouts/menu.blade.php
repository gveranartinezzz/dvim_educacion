<!-- =========================
     MENÚ PRINCIPAL
========================= -->
<nav class="navbar navbar-expand-lg navbar-dark shadow"
     style="
        background: linear-gradient(90deg, #54063e 0%, #7d0a5d 100%);
        padding: 12px 0;
        position: sticky;
        top: 0;
        z-index: 1020;
     ">

    <div class="container-fluid">

        <!-- =========================
             LOGO
        ========================= -->
        <a class="navbar-brand" href="{{ url('/inicio') }}">
            <img src="{{ asset('img/logo.png') }}" alt="DIVM" height="36" class="me-2 bg-white rounded-circle p-1" style="vertical-align: middle;">
            <span class="fw-bold text-white"
                  style="
                    letter-spacing: 1px;
                    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
                  ">
                DIVM
                <span style="font-weight: 300; opacity: 0.9;">
                    | Educación Especial
                </span>
            </span>
        </a>

        <!-- =========================
             BOTÓN RESPONSIVE
        ========================= -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menu"
                aria-controls="menu"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- =========================
             OPCIONES
        ========================= -->
        <div class="collapse navbar-collapse" id="menu">

            <!-- Lista principal de navegación -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <!-- INICIO -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('inicio') ? 'active-link' : '' }}"
                       href="{{ url('/inicio') }}">
                        Inicio
                    </a>
                </li>
                
                @if(session('rol') === 'Administrador')
                <!-- USUARIOS -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('usuarios*') ? 'active-link' : '' }}"
                       href="{{ url('/usuarios') }}">
                        Usuarios
                    </a>
                </li>
                @endif

                <!-- PACIENTES -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('registro*') ? 'active-link' : '' }}"
                       href="{{ url('/registro') }}">
                        Pacientes
                    </a>
                </li>

                <!-- PLANES -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('plan*') ? 'active-link' : '' }}"
                       href="{{ url('/plan') }}">
                        Planes
                    </a>
                </li>

                <!-- DISTRIBUCIÓN -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('distribucion*') ? 'active-link' : '' }}"
                       href="{{ url('/distribucion') }}">
                        Distribución
                    </a>
                </li>

                <!-- INFORMES -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('informe*') ? 'active-link' : '' }}"
                       href="{{ url('/informe') }}">
                        Informes
                    </a>
                </li>

                <!-- AVANCES -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('avances*') ? 'active-link' : '' }}"
                       href="{{ url('/avances') }}">
                        Avances
                    </a>
                </li>

                <!-- VALORACIÓN -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('valoracion*') ? 'active-link' : '' }}"
                       href="{{ url('/valoracion') }}">
                        Valoración
                    </a>
                </li>

                <!-- RECOMENDACIONES -->
                <li class="nav-item">
                    <a class="nav-link text-white px-3 menu-link {{ request()->is('recomendaciones*') ? 'active-link' : '' }}"
                       href="{{ url('/recomendaciones') }}">
                        Recomendaciones
                    </a>
                </li>

            </ul>

            <!-- =========================
                 ROL Y CERRAR SESIÓN
            ========================= -->
            <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">

                <span class="badge me-3 text-uppercase"
                      style="
                        background-color: rgba(255, 45, 45, 0.2);
                        font-weight: 300;
                      ">
                    {{ session('rol', 'Invitado') }}
                </span>

                <!-- CERRAR SESIÓN -->
<a class="btn text-white fw-bold" 
   style="background-color: #dc3545; border-color: #dc3545; transition: background-color 0.2s;" 
   href="{{ url('/logout') }}">
    Cerrar Sesión
</a>

            </div>

        </div>

    </div>

</nav>