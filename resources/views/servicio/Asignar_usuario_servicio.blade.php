@extends('adminlte::page')

@section('title', 'Asignar Personal')

@section('content')
<div class="container-fluid pt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title text-uppercase font-weight-bold">
                <i class="fas fa-user-plus mr-2"></i> Asignar Personal al Servicio
            </h3>
        </div>
        
        <div class="card-body">
            <div class="alert alert-info border-0 shadow-sm mb-4" style="border-left: 5px solid #0056b3 !important;">
                <h5 class="mb-1"><strong>SERVICIO:</strong> {{ $servicio->nombre }}</h5>
                <p class="mb-0 text-muted"><strong>DETALLES:</strong> {{ $servicio->descripcion }}</p>
            </div>

            <form action="{{ url('/servicio/' . $servicio->id . '/usuarioservicio') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-secondary">1. Seleccionar Usuario</label>
                            <select name="usuario_id" class="form-control select2" required>
                                <option value="">-- Seleccione un médico o empleado --</option>
                                @foreach ($usuarios as $u)
                                    <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-secondary">2. Descripción del Cargo</label>
                            <input type="text" name="descripcion_usuario_servicio" class="form-control" placeholder="Ej: Médico de turno, Residente..." required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="font-weight-bold text-secondary">3. Estado Inicial</label>
                            <select name="estado" class="form-control">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block mb-3 font-weight-bold shadow-sm">
                            <i class="fas fa-check-circle mr-1"></i> Confirmar
                        </button>
                    </div>
                </div>
            </form>

            <hr class="my-5">

            <h4 class="mb-3 text-secondary"><i class="fas fa-users mr-2"></i> Personal Actualmente en el Servicio</h4>
            <div class="table-responsive">
                <table class="table table-hover border shadow-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre del Médico / Empleado</th>
                            <th>Cargo / Descripción</th>
                            <th>Estado</th>
                            <th>Fecha Ingreso</th>
                            <th class="text-center bg-secondary">Acciones</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servicio->usuarios as $userAsignado)
                            <tr>
                                <td class="font-weight-bold">{{ $userAsignado->nombre }}</td>
                                <td>{{ $userAsignado->pivot->descripcion_usuario_servicio }}</td>
                                <td>
                                    <span class="badge {{ strtolower($userAsignado->pivot->estado) == 'activo' ? 'badge-success' : 'badge-danger' }} px-3 py-2">
                                        {{ strtoupper($userAsignado->pivot->estado) }}
                                    </span>
                                </td>
                                <td>{{ date('d/m/Y', strtotime($userAsignado->pivot->fecha_ingreso)) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('turnos.create', ['usuario_id' => $userAsignado->id, 'servicio_id' => $servicio->id]) }}" 
                                       class="btn btn-sm btn-info shadow-sm font-weight-bold">
                                        <i class="fas fa-calendar-plus mr-1"></i> Asignar Turno
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-user-slash fa-2x mb-3 d-block"></i>
                                    No hay personal registrado en este servicio todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white text-right">
            <a href="{{ route('servicio.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left mr-1"></i> Volver a la lista de servicios
            </a>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table thead th { vertical-align: middle; }
    .btn-info { background-color: #17a2b8; border-color: #17a2b8; }
    .btn-info:hover { background-color: #138496; }
</style>
@stop