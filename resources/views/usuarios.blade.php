@extends('layouts.app')

@section('title', 'Usuarios - DIVM')

@section('modulo', 'Módulo Usuarios')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'alta', 'icono' => 'bi-person-plus-fill', 'texto' => 'Registrar Usuario', 'activo' => true])
@include('layouts.tab', ['id' => 'lista', 'icono' => 'bi-people-fill', 'texto' => 'Lista de Usuarios'])
@include('layouts.tab', ['id' => 'roles', 'icono' => 'bi-shield-lock-fill', 'texto' => 'Roles y Permisos'])
@include('layouts.tab', ['id' => 'guias', 'icono' => 'bi-journal-bookmark-fill', 'texto' => 'Guías e Indicadores'])
@endsection

@section('content')
<div class="container my-4">

    <div class="tab-content">

        <!-- ============================= -->
        <!-- REGISTRAR USUARIO -->
        <!-- ============================= -->
        <div class="tab-pane fade show active" id="panel-alta" role="tabpanel">

            <div class="card-table mb-4">

                <div class="titulo-banner">

                    <h3>
                        <i class="bi bi-person-plus-fill"></i>
                        Registrar Nuevo Usuario
                    </h3>

                    <p class="mb-0 small">
                        Crea la cuenta de una persona del equipo y define qué puede hacer en el sistema
                    </p>

                </div>

                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf

                    <span class="section-label">
                        Datos Personales
                    </span>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Nombre(s):</span>
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre(s)" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Ap. Paterno:</span>
                                <input type="text" name="apaterno" class="form-control" placeholder="Apellido paterno" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Ap. Materno:</span>
                                <input type="text" name="amaterno" class="form-control" placeholder="Apellido materno" required>
                            </div>
                        </div>
                    </div>

                    <span class="section-label mt-4">
                        Acceso al Sistema
                    </span>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Correo:</span>
                        <input type="email" name="correo" class="form-control" placeholder="correo@ejemplo.com" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Contraseña:</span>
                        <input type="password" id="txtPassword" name="contrasena" class="form-control" placeholder="Mínimo 8 caracteres" minlength="8" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Rol:</span>
                                <select name="rol" class="form-select">
                                    <option value="Personal">Personal</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Estado:</span>
                                <select name="estado" class="form-select">
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-custom w-100 py-2">
                        <i class="bi bi-save me-1"></i> Guardar Usuario
                    </button>
                </form>

            </div>

        </div>

        <!-- ============================= -->
        <!-- LISTA DE USUARIOS -->
        <!-- ============================= -->
        <div class="tab-pane fade" id="panel-lista" role="tabpanel">

            <div class="card-table mb-4">

                <span class="section-label">
                    <i class="bi bi-people-fill me-2"></i>
                    Usuarios Registrados
                </span>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($usuarios as $u)
                                <tr>
                                    <td class="fw-bold" style="color: rgb(84, 6, 62);">{{ $u->nombre }} {{ $u->apaterno }} {{ $u->amaterno ?? '' }}</td>
                                    <td>{{ $u->correo }}</td>
                                    <td><span class="badge diagnostico">{{ $u->rol }}</span></td>
                                    <td>
                                        <span class="badge {{ $u->estado === 'Activo' ? 'estado-activo' : 'estado-inactivo' }}">{{ $u->estado }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if(session('rol') === 'Administrador')
                                            <a href="{{ url('/usuarios/'.$u->id_usuario.'/editar') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Editar</a>
                                            <form action="{{ url('/usuarios/'.$u->id_usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- ============================= -->
        <!-- ROLES Y PERMISOS -->
        <!-- ============================= -->
        <div class="tab-pane fade" id="panel-roles" role="tabpanel">

            <div class="card-table mb-4">

                <div class="titulo-banner">

                    <h3>
                        <i class="bi bi-shield-lock-fill"></i>
                        Roles y Permisos
                    </h3>

                    <p class="mb-0 small">
                        Qué puede hacer cada rol dentro del sistema
                    </p>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered align-middle text-center">

                        <thead class="table-light">
                            <tr>
                                <th class="text-start">Acción</th>
                                <th>Administrador</th>
                                <th>Personal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="text-start">Inicio</td>
                                <td>✅</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td class="text-start">Registrar y consultar (Pacientes, Planes, Recomendaciones, Informes, Valoración, Avances y Distribución)</td>
                                <td>✅</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td class="text-start">Editar o eliminar (Pacientes, Planes, Recomendaciones e Informes)</td>
                                <td>✅</td>
                                <td>❌</td>
                            </tr>
                            <tr>
                                <td class="text-start">Módulo Usuarios</td>
                                <td>✅</td>
                                <td>❌</td>
                            </tr>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- ============================= -->
        <!-- GUÍAS E INDICADORES -->
        <!-- ============================= -->
        <div class="tab-pane fade" id="panel-guias" role="tabpanel">
@php
$gTitulo = 'Guías e indicadores de Usuarios';
$gIntro  = 'Cómo dar de alta y mantener seguras las cuentas del sistema.';
$gInd = [
    ['Usuarios registrados', $usuarios->count(), 'bi-people-fill'],
    ['Activos', $usuarios->where('estado', 'Activo')->count(), 'bi-person-check-fill'],
    ['Inactivos', $usuarios->where('estado', 'Inactivo')->count(), 'bi-person-dash-fill'],
    ['Administradores', $usuarios->where('rol', 'Administrador')->count(), 'bi-shield-lock-fill'],
];
$gGuias = [
    ['Alta de usuarios', 'bi-person-plus', [
        'Solo los <strong>administradores</strong> registran, editan o eliminan usuarios.',
        'Quien crea su cuenta desde el inicio de sesión queda <strong>Inactivo</strong>: revísalo y actívalo en Editar → Estado.',
        'Cada usuario necesita un correo propio y distinto.',
        'Elige el rol: <strong>Administrador</strong> (acceso total) o <strong>Personal</strong>.',
    ]],
    ['Contraseñas y seguridad', 'bi-shield-check', [
        'La contraseña debe tener al menos 6 caracteres; mejor si mezcla letras y números.',
        'Cada usuario nuevo confirma su correo con un código de 6 dígitos al entrar.',
        'No compartas tu contraseña con nadie.',
    ]],
    ['Estado de la cuenta', 'bi-toggle-on', [
        '<strong>Activo:</strong> puede entrar al sistema.',
        '<strong>Inactivo:</strong> no puede iniciar sesión; su registro se conserva.',
        'Pon en Inactivo a quien ya no trabaje en el centro, en lugar de compartir su cuenta.',
    ]],
];
@endphp
@include('layouts.guias', ['titulo' => $gTitulo, 'intro' => $gIntro, 'indicadores' => $gInd, 'guias' => $gGuias])
        </div>

    </div>

</div>
@endsection
