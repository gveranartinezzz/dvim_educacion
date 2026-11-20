<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva contraseña - DIVM</title>
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
            <div class="verify-icono"><i class="bi bi-shield-lock-fill fs-2" style="color:#84063e;"></i></div>
            <h4 class="titulo-principal">Crea tu nueva contraseña</h4>
            <p class="small" style="color:#4a2a3f;">Escribe el código de 6 dígitos que enviamos a tu correo y elige una contraseña nueva.</p>
        </div>

        <div class="correo-hint">
            <i class="bi bi-envelope-fill me-2"></i>
            Código enviado a: <strong>{{ $correo }}</strong> (vence en {{ $minutos }} minutos)
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

        <form action="{{ route('recuperar.restablecer') }}" method="POST" id="formNueva">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold" style="color:#54063e;">Código de 6 dígitos</label>
                <input type="text" name="codigo" id="codigo" class="form-control text-center fw-bold"
                       style="font-size:1.6rem; letter-spacing:10px; color:#54063e;"
                       inputmode="numeric" maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code"
                       placeholder="······" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" style="color:#54063e;">Contraseña nueva</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" minlength="6"
                           autocomplete="new-password" placeholder="Mínimo 6 caracteres" required>
                    <button type="button" class="btn btn-outline-secondary ver-clave" data-target="password" aria-label="Mostrar u ocultar contraseña">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" style="color:#54063e;">Repite la contraseña</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" minlength="6"
                           autocomplete="new-password" placeholder="Escríbela otra vez" required>
                    <button type="button" class="btn btn-outline-secondary ver-clave" data-target="password_confirmation" aria-label="Mostrar u ocultar contraseña">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-generar w-100 py-2">
                <i class="bi bi-check-circle me-2"></i>Cambiar contraseña
            </button>
        </form>

        <div class="text-center mt-4">
            <form action="{{ route('recuperar.reenviar') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link small p-0" style="color:#84063e;text-decoration:none;font-weight:700;">
                    <i class="bi bi-arrow-repeat me-1"></i>Reenviar código
                </button>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small" style="color:#4a2a3f;"><i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Solo números en el código
    document.getElementById('codigo').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
    });

    // Mostrar / ocultar contraseña
    document.querySelectorAll('.ver-clave').forEach(function (b) {
        b.addEventListener('click', function () {
            var campo = document.getElementById(b.dataset.target);
            var ver = campo.type === 'password';
            campo.type = ver ? 'text' : 'password';
            b.querySelector('i').className = ver ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    });

    // Aviso antes de enviar si no coinciden
    document.getElementById('formNueva').addEventListener('submit', function (e) {
        if (document.getElementById('password').value !== document.getElementById('password_confirmation').value) {
            e.preventDefault();
            alert('Las contraseñas no coinciden.');
        }
    });
</script>
</body>
</html>
