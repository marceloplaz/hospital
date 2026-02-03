@extends('adminlte::page')

@section('title', 'Asignar Personal')

@section('content')
<div class="container-fluid pt-4">
    {{-- Cambiado de card-primary a card-health-outline para usar el borde verde --}}
    <div class="card card-health-outline shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h3 class="card-title text-health text-bold text-uppercase">
                <i class="fas fa-user-plus mr-2"></i> Asignar Personal al Servicio
            </h3>
        </div>
        
        <div class="card-body">
            {{-- Alerta con estilo suave verde --}}
            <div class="alert alert-health-soft border-0 shadow-sm mb-4">
                <h5 class="mb-1 text-health"><strong><i class="fas fa-hospital mr-2"></i>SERVICIO:</strong> {{ $servicio->nombre }}</h5>
                <p class="mb-0 text-muted"><strong>DETALLES:</strong> {{ $servicio->descripcion }}</p>
            </div>

            <form action="{{ url('/servicio/' . $servicio->id . '/usuarioservicio') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group">
                        <label class="text-muted text-xs text-uppercase font-weight-bold">1. Seleccionar Usuario</label>
                        <select name="usuario_id" id="usuario_id" class="form-control select2-buscar" required>
                            <option value="">Buscar médico o empleado...</option>
                            @foreach ($usuarios as $u)
                                <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-xs text-uppercase">2. Descripción del Cargo</label>
                            <input type="text" name="descripcion_usuario_servicio" class="form-control" placeholder="Ej: Médico de turno, Residente..." required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted text-xs text-uppercase">3. Estado Inicial</label>
                            <select name="estado" class="form-control">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-health btn-block mb-3 shadow-sm">
                            <i class="fas fa-check-circle mr-1"></i> Confirmar
                        </button>
                    </div>
                </div>
            </form>

            <hr class="my-5">

            <h4 class="mb-3 text-muted"><i class="fas fa-users mr-2 text-health"></i> Personal Actualmente en el Servicio</h4>
            <div class="table-responsive">
                <table class="table table-hover mb-0 shadow-xs border">
                    <thead class="thead-health">
                        <tr>
                            <th>Nombre del Médico / Empleado</th>
                            <th>Cargo / Descripción</th>
                            <th class="text-center">Estado</th>
                            <th>Fecha Ingreso</th>
                            <th class="text-center">Acciones</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servicio->usuarios as $userAsignado)
                            <tr>
                                <td class="align-middle font-weight-bold text-dark">{{ $userAsignado->nombre }}</td>
                                <td class="align-middle text-muted">{{ $userAsignado->pivot->descripcion_usuario_servicio }}</td>
                                <td class="align-middle text-center">
                                    <span class="badge {{ strtolower($userAsignado->pivot->estado) == 'activo' ? 'badge-success' : 'badge-danger' }} px-3 py-2 shadow-xs">
                                        {{ strtoupper($userAsignado->pivot->estado) }}
                                    </span>
                                </td>
                                <td class="align-middle">{{ date('d/m/Y', strtotime($userAsignado->pivot->fecha_ingreso)) }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('turnos.create', ['usuario_id' => $userAsignado->id, 'servicio_id' => $servicio->id]) }}" 
                                       class="btn btn-sm btn-white text-info border shadow-xs font-weight-bold">
                                        <i class="fas fa-calendar-plus mr-1"></i> Asignar Turno
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-user-slash fa-2x mb-3 d-block text-gray"></i>
                                    No hay personal registrado en este servicio todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white text-right">
            <a href="{{ route('servicio.index') }}" class="btn btn-outline-secondary px-4 border-0">
                <i class="fas fa-arrow-left mr-1"></i> Volver a la lista de servicios
            </a>
        </div>
    </div>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
<style>
    :root {
        --health-color: #28a745;
        --health-hover: #218838;
        --health-bg: #eafaf1;
    }
    .text-health { color: var(--health-color) !important; }
    .btn-health { background-color: var(--health-color); color: white; border-radius: 6px; font-weight: 600; }
    .btn-health:hover { background-color: var(--health-hover); color: white; transform: translateY(-1px); transition: 0.2s; }
    
    .card-health-outline { border-top: 5px solid var(--health-color); border-radius: 12px; }
    
    .alert-health-soft { background-color: var(--health-bg); border-left: 5px solid var(--health-color) !important; color: #155724; }
    
    .thead-health th { 
        background-color: #f8fbf9; 
        color: #495057; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e0f2e9 !important;
    }

    /* Personalización Select2 en verde */
    .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
        background-color: var(--health-color) !important;
    }
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
        border: 1px solid #ced4da !important;
    }
    .shadow-xs { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-buscar').select2({
            theme: 'bootstrap4',
            placeholder: "-- Buscar médico o empleado --",
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return "No se encontraron resultados"; }
            }
        });
    });
</script>
@stop