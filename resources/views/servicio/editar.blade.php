@extends('adminlte::page')

@section('title', 'Editar Servicio')

@section('content_header')
    <h1><i class="fas fa-edit mr-2 text-warning"></i>Modificar Servicio</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        Datos del Servicio: <span class="text-primary">{{ $servicio->nombre }}</span>
                    </h3>
                </div>
                
                <form action="{{ route('servicio.update', $servicio->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre">Nombre del Servicio</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-hospital"></i></span>
                                </div>
                                <input type="text" name="nombre" class="form-control" id="nombre" 
                                       value="{{ old('nombre', $servicio->nombre) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" class="form-control" id="descripcion" rows="3" required>{{ old('descripcion', $servicio->descripcion) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="cantidad_pacientes">Capacidad de Pacientes</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                </div>
                                <input type="number" name="cantidad_pacientes" class="form-control" id="cantidad_pacientes" 
                                       value="{{ old('cantidad_pacientes', $servicio->cantidad_pacientes) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('servicio.index') }}" class="btn btn-secondary shadow-sm">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning font-weight-bold shadow-sm ml-2">
                            <i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR DATOS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop