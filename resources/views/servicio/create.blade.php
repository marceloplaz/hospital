@extends('adminlte::page')

@section('title', 'Nuevo Servicio')

@section('content_header')
    <h1>Agregar Nuevo Servicio</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {{-- La ruta 'servicio.store' se crea automáticamente por el Route::resource --}}
        <form action="{{ route('servicio.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nombre del Servicio</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Cantidad de Pacientes</label>
                <input type="number" name="cantidad_pacientes" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('servicio.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop