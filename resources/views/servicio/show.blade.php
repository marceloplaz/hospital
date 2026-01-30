@extends('adminlte::page')

@section('title', 'Detalles del Servicio')

@section('content_header')
    <h1><i class="fas fa-info-circle mr-2 text-info"></i>Detalles del Servicio</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Bloque de Información General --}}
        <div class="col-md-4">
            <div class="card card-info card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Información General</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nombre:</b> <span class="float-right text-primary font-weight-bold">{{ $servicio->nombre }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Capacidad:</b> <span class="float-right badge badge-primary">{{ $servicio->cantidad_pacientes }} Pacientes</span>
                        </li>
                        <li class="list-group-item">
                            <b>Descripción:</b> <br>
                            <p class="text-muted mt-2">{{ $servicio->descripcion }}</p>
                        </li>
                    </ul>
                    <a href="{{ route('servicio.index') }}" class="btn btn-secondary btn-block"><b>Volver a la lista</b></a>
                </div>
            </div>
        </div>

        {{-- Bloque de Personal Asignado (Relación con Usuarios) --}}
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info">
                    <h3 class="card-title font-weight-bold">Personal Asignado a este Servicio</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fecha Ingreso</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servicio->usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->nombre }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->pivot->fecha_ingreso ?? 'No registrada' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $usuario->pivot->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                        {{ $usuario->pivot->estado == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay personal asignado a este servicio todavía.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ url('/servicio/' . $servicio->id . '/usuarioservicio') }}" class="btn btn-primary btn-sm float-right">
                        <i class="fas fa-user-plus mr-1"></i> Asignar nuevo personal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop