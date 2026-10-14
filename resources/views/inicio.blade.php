<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>DIVM - Inicio de Sesión</title>
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

    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            
            <!-- Carrusel con tus imágenes originales -->
            <div class="col-md-6 mb-4 mb-md-0">
                <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner text-center">
                        <div class="carousel-item active">
                            <img src="{{ asset('img/tarjeta.jpg') }}" alt="Tarjeta DIVM" class="d-block mx-auto img-fluid rounded" style="max-width: 500px; height: 350px; object-fit: contain;">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('img/centro.jpg') }}" alt="Nuestro centro" class="d-block mx-auto img-fluid rounded" style="max-width: 500px; height: 350px; object-fit: contain;">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('img/ninos.png') }}" alt="Todos diferentes, todos iguales" class="d-block mx-auto img-fluid rounded" style="max-width: 500px; height: 350px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Inicio de Sesión -->
            <div class="col-md-5">
                <div class="login-container">
                    <div class="titulo-banner">
                        <h4 class="mb-0">INICIO DE SESIÓN</h4>
                    </div>
                    
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        @if(session('error'))<div class="alert alert-danger py-2">{{ session('error') }}</div>@endif
                        @if($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" value="{{ old('correo') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-divm w-100 py-2">Ingresar al Sistema</button>
                    </form>

                    <div class="text-center mt-3" style="font-size: 14px;">
                        ¿Olvidaste tu contraseña? <span style="color: #84063e; font-weight: bold;">Solicita el cambio a un administrador.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>