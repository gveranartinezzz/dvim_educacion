<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificación en 2 pasos - DIVM</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
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

<div class="container mt-5 d-flex justify-content-center">
    <div class="verify-card">

        <div class="text-center mb-3">
            <div class="verify-icono"><i class="bi bi-shield-lock-fill fs-2" style="color:#84063e;"></i></div>
            <h4 class="titulo-principal">Verificación en 2 pasos</h4>
            <p class="text-muted small">Escribe el código de 6 dígitos que enviamos a tu correo.</p>
        </div>

        <div class="correo-hint">
            <i class="bi bi-envelope-fill me-2"></i>
            Código enviado a: <strong>{{ $correo }}</strong>
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

        <form action="{{ route('verificar.post') }}" method="POST" id="formVerificar">
            @csrf
            <input type="hidden" name="codigo" id="codigoCompleto">

            <div class="code-inputs">
                @for($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" class="digit" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" aria-label="Dígito {{ $i + 1 }}">
                @endfor
            </div>

            <button type="submit" class="btn btn-generar w-100 py-2" id="btnVerificar">
                <i class="bi bi-check-circle me-2"></i>Verificar código
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted small mb-1">El código vence en: <span id="contador">--:--</span></p>
            <form action="{{ route('verificar.reenviar') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link small p-0" style="color:#84063e;text-decoration:none;font-weight:700;">
                    <i class="bi bi-arrow-repeat me-1"></i>Reenviar código
                </button>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small text-muted"><i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var digitos = document.querySelectorAll('.digit');
    digitos[0].focus();

    digitos.forEach(function (input, i) {
        input.addEventListener('input', function () {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value && i < digitos.length - 1) digitos[i + 1].focus();
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !input.value && i > 0) digitos[i - 1].focus();
        });
    });

    // Pegar el código completo en la primera casilla
    digitos[0].addEventListener('paste', function (e) {
        e.preventDefault();
        var pegado = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
        pegado.split('').forEach(function (c, i) { if (digitos[i]) digitos[i].value = c; });
        if (digitos[Math.min(pegado.length, 6) - 1]) digitos[Math.min(pegado.length, 6) - 1].focus();
    });

    document.getElementById('formVerificar').addEventListener('submit', function (e) {
        var codigo = Array.from(digitos).map(function (d) { return d.value; }).join('');
        if (codigo.length < 6) { e.preventDefault(); alert('Escribe los 6 dígitos del código.'); return; }
        document.getElementById('codigoCompleto').value = codigo;
    });

    // Cuenta regresiva (los segundos reales los calcula el servidor)
    var segundos = {{ (int) $segundos }};
    var contador = document.getElementById('contador'), boton = document.getElementById('btnVerificar');
    function pintar() {
        if (segundos <= 0) {
            contador.textContent = 'Vencido'; contador.style.color = '#888';
            boton.disabled = true; boton.textContent = 'Código vencido'; return;
        }
        var m = String(Math.floor(segundos / 60)).padStart(2, '0'), s = String(segundos % 60).padStart(2, '0');
        contador.textContent = m + ':' + s;
    }
    pintar();
    setInterval(function () { if (segundos > 0) { segundos--; pintar(); } }, 1000);
</script>
</body>
</html>
