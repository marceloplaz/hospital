@extends('adminlte::page')

@section('title', 'Asignar Personal')

@section('content')
<div class="container-fluid pt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title text-uppercase font-weight-bold">
                <i class="fas fa-user-plus mr-2"></i> Asignar Personal
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
                            <label class="font-weight-bold text-secondary">Seleccionar Usuario</label>
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
                            <label class="font-weight-bold text-secondary">Descripción del Cargo</label>
                            <input type="text" name="descripcion_usuario_servicio" class="form-control" placeholder="Ej: Médico de turno, Residente..." required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="font-weight-bold text-secondary">Estado Inicial</label>
                            <select name="estado" class="form-control">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block mb-3 font-weight-bold shadow-sm">
                            Confirmar
                        </button>
                    </div>
                </div>
            </form>

            <hr class="my-5">

            <h4 class="mb-3 text-secondary"><i class="fas fa-users mr-2"></i> Personal Asignado</h4>
            <div class="table-responsive">
                <table class="table table-hover border">
                    <thead class="thead-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha Ingreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servicio->usuarios as $userAsignado)
                            <tr>
                                <td class="font-weight-bold">{{ $userAsignado->nombre }}</td>
                                <td>{{ $userAsignado->pivot->descripcion_usuario_servicio }}</td>
                                <td>
                                    @if(strtolower($userAsignado->pivot->estado) == 'activo')
                                        <span class="badge badge-success px-3 py-2">ACTIVO</span>
                                    @else
                                        <span class="badge badge-danger px-3 py-2">INACTIVO</span>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y', strtotime($userAsignado->pivot->fecha_ingreso)) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay personal registrado en este servicio.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('servicio.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </div>
</div>
@stop