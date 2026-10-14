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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    @stack('head')
    <!-- Hoja de estilo única del sistema -->
    <link rel="stylesheet" href="{{ asset('css/divm.css') }}">

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
        // Los mensajes de "guardado" se cierran solos a los 4 segundos
        document.querySelectorAll('.auto-cerrar').forEach(function (a) {
            setTimeout(function () { bootstrap.Alert.getOrCreateInstance(a).close(); }, 4000);
        });
    </script>

    @stack('scripts')
    @yield('scripts')

</body>

</html>
