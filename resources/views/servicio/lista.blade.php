@extends('adminlte::page')

@section('title', 'Gestión de Servicios')

@section('content_header')
    <h1><i class="fas fa-hospital-symbol mr-2"></i>Gestión de Servicios - Hospital</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- 1. FORMULARIO DE REGISTRO ESTILIZADO --}}
    <div class="card card-primary card-outline shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Registrar Nuevo Servicio</h3>
        </div>
        <div class="card-body bg-light">
            <form action="/servicio" method="post">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="text-secondary small font-weight-bold">NOMBRE DEL SERVICIO</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Pediatría" required>
                    </div>
                    <div class="col-md-4">
                        <label class="text-secondary small font-weight-bold">DESCRIPCIÓN</label>
                        <input type="text" name="descripcion" class="form-control" placeholder="Breve descripción..." required>
                    </div>
                    <div class="col-md-2">
                        <label class="text-secondary small font-weight-bold">CANT. PACIENTES</label>
                        <input type="number" name="cantidad_pacientes" class="form-control" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success btn-block font-weight-bold">
                            <i class="fas fa-save mr-1"></i> GUARDAR SERVICIO
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. TABLA DE SERVICIOS --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-white border-bottom">
                    <tr>
                        <th style="width: 80px">ID</th>
                        <th>NOMBRE</th>
                        <th>DESCRIPCIÓN</th>
                        <th class="text-center">CAPACIDAD</th>
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($servicio as $s)
                    <tr>
                        <td><span class="badge badge-light border">#{{ $s->id }}</span></td>
                        <td class="font-weight-bold text-primary">{{ $s->nombre }}</td>
                        <td class="text-muted small">{{ $s->descripcion }}</td>
                        <td class="text-center">{{ $s->cantidad_pacientes }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('servicio.show', $s->id) }}" class="btn btn-info btn-sm shadow-sm" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ url('/servicio/' . $s->id . '/usuarioservicio') }}" class="btn btn-primary btn-sm shadow-sm ml-1" title="Asignar Usuario">
                                    <i class="fas fa-user-plus mr-1"></i> Asignar
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
