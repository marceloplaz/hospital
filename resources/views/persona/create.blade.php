@extends('adminlte::page')

@section('title', 'Nuevo Personal')

@section('content')
<div class="container-fluid pt-4">
    {{-- Mensajes de Error de Validación --}}
@if(session('error'))
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
    </div>
@endif
    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-outline card-success shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title text-success font-weight-bold">
                <i class="fas fa-user-plus mr-2"></i> REGISTRAR NUEVO PERSONAL
            </h3>
        </div>

        <form action="{{ route('personas.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <h5 class="text-muted border-bottom pb-2 mb-4">Datos de Identidad</h5>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Nombres</label>
                        <input type="text" name="nombres" class="form-control" value="{{ old('nombres') }}" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Cédula de Identidad (CI)</label>
                        <input type="text" name="ci" class="form-control" value="{{ old('ci') }}" required>
                    </div>
                </div>

                <h5 class="text-muted border-bottom pb-2 mb-4 mt-3">Información de Acceso y Contacto</h5>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Correo Electrónico (Para iniciar sesión)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="ejemplo@hospital.com" required>
                        <small class="text-info">La contraseña por defecto será el número de CI.</small>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Género</label>
                        <select name="genero" class="form-control">
                            <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>
                </div>

                <h5 class="text-muted border-bottom pb-2 mb-4 mt-3">Datos Laborales</h5>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label>Tipo de Trabajador</label>
                        <select name="tipo_trabajador" class="form-control">
                            <option value="Médico">Médico</option>
                            <option value="Enfermero">Enfermero</option>
                            <option value="Administrativo">Administrativo</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Tipo de Salario</label>
                        <select name="tipo_salario" class="form-control">
                            <option value="SUS">SUS</option>
                            <option value="TGN">TGN</option>
                            <option value="Contrato">Contrato</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Salario Mensual</label>
                        <input type="number" step="0.01" name="salario" class="form-control" value="{{ old('salario') }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Nacionalidad</label>
                        <input type="text" name="nacionalidad" class="form-control" value="{{ old('nacionalidad', 'Boliviana') }}">
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light text-right">
                <a href="{{ route('personas.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save mr-1"></i> Guardar Personal
                </button>
            </div>
        </form>
    </div>
</div>
@stop