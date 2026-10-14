@extends('layouts.app')


@section('content')
<div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-7 bg-white p-4 rounded shadow">
                <h3 class="text-center mb-4" style="color: #c5005a;">Actualizar Información de Usuario</h3>
                
                <form action="{{ url('/usuarios/'.$usuario->id_usuario) }}" method="POST">
@csrf
@method('PUT')
                    <input type="hidden" name="id_usuario" value="{{ $usuario->id_usuario }}">

                    <div class="input-group mb-3">
                        <span class="input-group-text">Nombre:</span>
                        <input type="text" name="nombre" class="form-control" value="{{ $usuario->nombre }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text">Ap. Paterno:</span>
                                <input type="text" name="apaterno" class="form-control" value="{{ $usuario->apaterno }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Correo:</span>
                        <input type="email" name="correo" class="form-control" value="{{ $usuario->correo }}" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Rol:</span>
                                <select name="rol" class="form-select">
                                    <option value="Personal" {{ $usuario->rol == 'Personal' ? 'selected' : '' }}>Personal</option>
                                    <option value="Administrador" {{ $usuario->rol == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">Estado:</span>
                                <select name="estado" class="form-select">
                                    <option value="Activo" {{ $usuario->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Inactivo" {{ $usuario->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="dos_factores" name="dos_factores_activado" value="1" {{ $usuario->dos_factores_activado ? 'checked' : '' }}>
                        <label class="form-check-label" for="dos_factores"><i class="bi bi-shield-lock-fill"></i> Verificación en dos pasos (código por correo al iniciar sesión)</label>
                    </div>

                    <div class="alert alert-info small">Deje la contraseña en blanco si no desea cambiarla.</div>
                    <div class="input-group mb-4">
                        <span class="input-group-text">Nueva Clave:</span>
                        <input type="password" name="nueva_contrasena" class="form-control" placeholder="Opcional">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ url('/usuarios') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-update px-4">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
