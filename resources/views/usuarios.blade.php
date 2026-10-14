@extends('layouts.app')

@section('modulo', 'Módulo Usuarios')

@section('sidebar_tabs')
@include('layouts.tab', ['id' => 'alta', 'icono' => 'bi-person-plus-fill', 'texto' => 'Registrar Usuario', 'activo' => true])
@include('layouts.tab', ['id' => 'lista', 'icono' => 'bi-people-fill', 'texto' => 'Lista de Usuarios'])
@include('layouts.tab', ['id' => 'roles', 'icono' => 'bi-shield-lock-fill', 'texto' => 'Roles y Permisos'])
@endsection

@section('content')
<div class="container mt-4">

    <div class="tab-content">

        <!-- REGISTRAR USUARIO -->
        <div class="tab-pane fade show active" id="panel-alta">

            <div class="contenido-panel">
<form action="{{ route('usuarios.store') }}" method="POST">
@csrf

                <h3 class="text-center mb-4" style="color:#c5005a;">
                    Registrar Nuevo Usuario
                </h3>

                <span class="section-label">
                    Datos Personales
                </span>

                <div class="input-group mb-3">
                    <span class="input-group-text">Nombre(s)</span>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">Ap. Paterno</span>
                            <input type="text" name="apaterno" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">Ap. Materno</span>
                            <input type="text" name="amaterno" class="form-control" required>
                        </div>
                    </div>

                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Correo</span>
                    <input type="email" name="correo" class="form-control" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Contraseña</span>
                    <input type="password" id="txtPassword" name="contrasena" class="form-control" required>
                </div>

                <div class="row mb-4">

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">Rol</span>

                            <select name="rol" class="form-select">
                                <option value="Personal">Personal</option>
                                <option value="Administrador">Administrador</option>
                            </select>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">Estado</span>

                            <select name="estado" class="form-select">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>

                        </div>
                    </div>

                </div>

                <button type="submit" class="btn btn-custom w-100 py-2">
                    Guardar Usuario
                </button>
</form>

            </div>

        </div>

        <!-- LISTA DE USUARIOS -->
        <div class="tab-pane fade" id="panel-lista">

            <div class="contenido-panel">

                <span class="section-label">
                    Usuarios Registrados
                </span>

                <div class="table-responsive">

                    <table class="table table-hover">

                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($usuarios as $u)
<tr>
<td>{{ $u->nombre }} {{ $u->apaterno }} {{ $u->amaterno ?? '' }}</td>
<td>{{ $u->correo }}</td>
<td>{{ $u->rol }}</td>
<td>{{ $u->estado }}</td>
<td>@if(session('rol') === 'Administrador')
<a href="{{ url('/usuarios/'.$u->id_usuario.'/editar') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Editar</a>
<form action="{{ url('/usuarios/'.$u->id_usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
@endif</td>
</tr>
@endforeach
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- ROLES Y PERMISOS -->
        <div class="tab-pane fade" id="panel-roles">

            <div class="contenido-panel">

                <h3 class="text-center mb-4" style="color:#54063e;">
                    Roles y Permisos
                </h3>

                <table class="table table-bordered text-center">

                    <thead class="table-light">
                        <tr>
                            <th>Módulo</th>
                            <th>Administrador</th>
                            <th>Personal</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Inicio</td>
                            <td>✅</td>
                            <td>✅</td>
                        </tr>

                        <tr>
                            <td>Usuarios</td>
                            <td>✅</td>
                            <td>❌</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
@endsection
