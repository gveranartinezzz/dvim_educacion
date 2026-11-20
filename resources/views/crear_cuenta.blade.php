<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>DIVM - Crear cuenta</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/divm.css') }}">
</head>
<body>

    <div class="container-fluid" style="background-color: rgba(255, 255, 255, 0.99); padding: 20px;">
        <div class="row align-items-center">
            <div class="col d-flex justify-content-center">
                <h2 class="titulo-principal mb-0">DIVM EDUCACIÓN ESPECIAL</h2>
            </div>
            <div class="col-auto ms-auto">
                <img src="{{ asset('img/logo.png') }}" style="width: 135px; height: auto;" alt="Logo DIVM">
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-container">

                    <div class="titulo-banner">
                        <h4 class="mb-0">CREAR CUENTA</h4>
                    </div>

                    @if(session('creada'))

                        <div class="alert alert-success">{{ session('creada') }}</div>
                        <a href="{{ route('login') }}" class="btn btn-divm w-100 py-2">Ir a iniciar sesión</a>

                    @else

                        <form action="{{ route('crear-cuenta.post') }}" method="POST">
                            @csrf

                            @if($errors->any())
                                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Nombre(s)</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" maxlength="100" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apellido paterno</label>
                                    <input type="text" name="apaterno" value="{{ old('apaterno') }}" class="form-control" maxlength="100" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apellido materno</label>
                                    <input type="text" name="amaterno" value="{{ old('amaterno') }}" class="form-control" maxlength="100" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="correo" value="{{ old('correo') }}" class="form-control" maxlength="150" autocomplete="email" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="contrasena" class="form-control" minlength="8" maxlength="72" autocomplete="new-password" required>
                                <div class="form-text" style="color: #5f5560;">Mínimo 8 caracteres.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirmar contraseña</label>
                                <input type="password" name="contrasena_confirmation" class="form-control" minlength="8" maxlength="72" autocomplete="new-password" required>
                            </div>

                            {{-- Campo trampa contra robots: las personas no lo ven --}}
                            <div style="position: absolute; left: -9999px;" aria-hidden="true">
                                <label>Sitio web</label>
                                <input type="text" name="sitio_web" tabindex="-1" autocomplete="off">
                            </div>

                            @if(config('divm.registro_aprobacion', true))
                                <p class="small" style="color: #4a2a3f;">Un administrador revisará tu cuenta y la activará antes de que puedas entrar.</p>
                            @endif

                            <button type="submit" class="btn btn-divm w-100 py-2">Crear cuenta</button>
                        </form>

                        <div class="text-center mt-3" style="font-size: 14px;">
                            ¿Ya tienes cuenta?
                            <a href="{{ route('login') }}" style="color: #84063e; font-weight: bold;">Inicia sesión</a>
                        </div>

                    @endif

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
