@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content')
<div class="container-fluid pt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- Lado Izquierdo: Información de Cuenta --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="https://ui-avatars.com/api/?name={{ $usuario->nombre }}&background=0D6EFD&color=fff" alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center font-weight-bold">{{ $usuario->nombre }}</h3>
                    <p class="text-muted text-center">{{ $usuario->email }}</p>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Tipo:</b> <a class="float-right text-primary">{{ $persona->tipo_trabajador ?? 'No asignado' }}</a>
                        </li>
                        <li class="list-group-item text-center">
                            <span class="badge {{ $usuario->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                {{ $usuario->estado == 1 ? 'CUENTA ACTIVA' : 'CUENTA INACTIVA' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Lado Derecho: Formulario Completo --}}
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i> Datos Personales Completos</h3>
                </div>
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- Fila 1: Apellidos y CI --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" value="{{ $persona->apellidos }}" placeholder="Ingrese sus apellidos">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>C.I. (Documento de Identidad)</label>
                                <input type="text" name="ci" class="form-control" value="{{ $persona->ci }}" required>
                            </div>
                        </div>

                        {{-- Fila 2: Teléfono y Género --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Teléfono / Celular</label>
                                <input type="text" name="telefono" class="form-control" value="{{ $persona->telefono }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Género</label>
                                <select name="genero" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="MASCULINO" {{ (strtoupper($persona->genero) == 'MASCULINO') ? 'selected' : '' }}>Masculino</option>
                                    <option value="FEMENINO" {{ (strtoupper($persona->genero) == 'FEMENINO') ? 'selected' : '' }}>Femenino</option>
                                    <option value="OTRO" {{ (strtoupper($persona->genero) == 'OTRO') ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>

                        {{-- Fila 3: Fecha Nacimiento y Nacionalidad --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $persona->fecha_nacimiento }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Nacionalidad</label>
                                <input type="text" name="nacionalidad" class="form-control" value="{{ $persona->nacionalidad }}" placeholder="Ej: Boliviana">
                            </div>
                        </div>

                        {{-- Fila 4: Dirección --}}
                        <div class="form-group">
                            <label>Dirección de Domicilio</label>
                            <textarea name="direccion" class="form-control" rows="2" required>{{ $persona->direccion }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success shadow-sm">
                            <i class="fas fa-save mr-1"></i> Actualizar Todo el Perfil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop