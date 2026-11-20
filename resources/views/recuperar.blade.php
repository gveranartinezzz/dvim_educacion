<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña - DIVM</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/divm.css') }}">
</head>
<body>

<div class="container-fluid" style="background-color: rgba(255,255,255,.99); padding: 20px;">
    <div class="row align-items-center">
        <div class="col d-flex justify-content-center">
            <h2 class="titulo-principal mb-0">DIVM EDUCACIÓN ESPECIAL</h2>
        </div>
        <div class="col-auto ms-auto">
            <img src="{{ asset('img/logo.png') }}" style="width: 135px; height: auto;" alt="Logo DIVM">
        </div>
    </div>
</div>

<div class="container mt-5 mb-5 d-flex justify-content-center">
    <div class="verify-card">

        <div class="text-center mb-3">
            <div class="verify-icono"><i class="bi bi-key-fill fs-2" style="color:#84063e;"></i></div>
            <h4 class="titulo-principal">Recuperar contraseña</h4>
            <p class="small" style="color:#4a2a3f;">Escribe el correo de tu cuenta y te enviaremos un código de 6 dígitos para crear una contraseña nueva.</p>
        </div>

        @if($modoPrueba)
            <div class="alert alert-warning py-2 small">
                <i class="bi bi-tools me-1"></i> <strong>Modo de pruebas:</strong> el correo no sale de la computadora.
                El código está en <code>storage/logs/laravel.log</code>.
            </div>
        @endif

        @if(session('ok'))<div class="alert alert-success py-2 small">{{ session('ok') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger py-2 small">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>@endif

        <form action="{{ route('recuperar.enviar') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold" style="color:#54063e;">Correo electrónico</label>
                <input type="email" name="correo" value="{{ old('correo') }}" class="form-control" placeholder="correo@ejemplo.com" autocomplete="email" required autofocus>
            </div>

            <button type="submit" class="btn btn-generar w-100 py-2">
                <i class="bi bi-envelope-paper me-2"></i>Enviar código
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small" style="color:#4a2a3f;"><i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
