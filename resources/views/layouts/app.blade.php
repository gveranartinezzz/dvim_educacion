<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DIVM Educación Especial')</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Bootstrap + Iconos (una sola vez para todo el sistema) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @stack('head')
    <!-- Hoja de estilo única del sistema -->
    <link rel="stylesheet" href="{{ asset('css/divm.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mejoras.css') }}">

</head>

<body>

    @include('layouts.menu')

    <div class="wrapper">

        @include('layouts.sidebar')

        <main id="content">

            @if(session('ok'))
                <div class="alert alert-success alert-dismissible fade show auto-cerrar" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('ok') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @yield('content')

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Altura real del menú superior (cambia si se parte en dos renglones):
        // el menú lateral fijo la usa para pegarse justo debajo.
        (function () {
            var nav = document.querySelector('nav.navbar');
            if (!nav) return;
            function medir() {
                document.documentElement.style.setProperty('--nav-h', nav.offsetHeight + 'px');
            }
            medir();
            window.addEventListener('resize', medir);
            if (window.ResizeObserver) new ResizeObserver(medir).observe(nav);
        })();

        // Menú lateral: recuerda la pestaña activa. Al guardar/borrar algo (la página se
        // recarga) o al refrescar, vuelve a la misma pestaña en vez de saltar a la primera.
        (function () {
            var botones = document.querySelectorAll('#dynamicSubmenu [data-bs-toggle="tab"]');
            if (!botones.length) return;
            var clave = 'divm-tab:' + location.pathname;

            botones.forEach(function (b) {
                b.addEventListener('shown.bs.tab', function () {
                    try { sessionStorage.setItem(clave, b.getAttribute('data-bs-target')); } catch (e) {}
                });
            });

            var nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
            var recarga = nav && nav.type === 'reload';
            var mismaPagina = false;
            try { mismaPagina = document.referrer && new URL(document.referrer).pathname === location.pathname; } catch (e) {}
            if (!recarga && !mismaPagina) return;   // si viene de otro módulo, empieza en la primera

            var destino = null;
            try { destino = sessionStorage.getItem(clave); } catch (e) {}
            if (!destino) return;
            botones.forEach(function (b) {
                if (b.getAttribute('data-bs-target') === destino) new bootstrap.Tab(b).show();
            });
        })();

        // Selector de paciente: al elegirlo se rellenan solos los datos que ya se capturaron en
        // Pacientes (padres, fecha de nacimiento, domicilio). Se puede corregir a mano; si se
        // vuelve a cambiar de paciente solo se reemplaza lo que no se haya tocado.
        (function () {
            var datos = { madre: 'madre', nombre_madre: 'madre', padre: 'padre', nombre_padre: 'padre',
                          fecha_nacimiento: 'nacimiento', domicilio: 'domicilio' };
            document.querySelectorAll('select[data-paciente-select]').forEach(function (sel) {
                var form = sel.closest('form');
                if (!form) return;
                var previa = sel.options[sel.selectedIndex] || null;   // paciente con el que abrió el formulario

                form.addEventListener('input', function (e) {          // lo escrito por la persona ya no se pisa
                    if (e.target && e.target.dataset && e.target.dataset.auto === '1') e.target.dataset.auto = '0';
                });

                sel.addEventListener('change', function () {
                    var op = sel.options[sel.selectedIndex];
                    if (!op || !op.value) return;
                    Object.keys(datos).forEach(function (campo) {
                        var el = form.querySelector('[name="' + campo + '"]');
                        if (!el) return;
                        var nuevo = op.getAttribute('data-' + datos[campo]) || '';
                        var anterior = previa ? (previa.getAttribute('data-' + datos[campo]) || '') : '';
                        // se reemplaza solo lo vacío, lo que puso el sistema o lo que sigue igual al paciente anterior;
                        // si el nuevo paciente no tiene ese dato, el campo queda vacío (no se arrastra el del anterior)
                        if (!el.value || el.dataset.auto === '1' || el.value === anterior) {
                            el.value = nuevo; el.dataset.auto = nuevo ? '1' : '0';
                        }
                    });
                    previa = op;
                });
            });
        })();

        // Los mensajes de "guardado" se cierran solos a los 4 segundos
        document.querySelectorAll('.auto-cerrar').forEach(function (a) {
            setTimeout(function () { (bootstrap.Alert.getInstance(a) || new bootstrap.Alert(a)).close(); }, 4000);
        });
    </script>

    <script src="{{ asset('js/formularios.js') }}?v=1"></script>

    @stack('scripts')
    @yield('scripts')

</body>

</html>
