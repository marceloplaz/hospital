@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Mis Servicios Asignados</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Descripción</th>
                    <th>Fecha Ingreso</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuario->servicios as $ser)
                    <tr>
                        <td>{{ $ser->nombre }}</td>
                        <td>{{ $ser->pivot->descripcion_usuario_servicio }}</td>
                        <td>{{ $ser->pivot->fecha_ingreso }}</td>
                        <td>
                            <span class="badge {{ $ser->pivot->estado == 'Activo' ? 'badge-success' : 'badge-danger' }}">
                                {{ $ser->pivot->estado }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No tienes servicios asignados aún.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop