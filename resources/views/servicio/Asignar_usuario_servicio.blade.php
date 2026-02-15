@extends('adminlte::page')

@section('title', 'Asignar Personal')

@section('content')
<div class="container-fluid pt-4">
    <div class="row">
        <div class="col-12">
            {{-- Tarjeta Principal --}}
            <div class="card card-health-outline shadow-lg border-0 overflow-hidden">
                
                {{-- Encabezado con degradado suave --}}
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-health text-bold text-uppercase mb-0">
                        <i class="fas fa-user-md mr-2"></i> Gestión de Personal del Servicio
                    </h3>
                    <span class="badge badge-pill badge-health-light px-3 py-2 text-health font-weight-bold">
                        <i class="fas fa-hospital-symbol mr-1"></i> Hospital Central
                    </span>
                </div>
                
                <div class="card-body">
                    {{-- Banner Informativo del Servicio --}}
                    <div class="info-banner mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-auto text-center px-4">
                                <div class="service-icon-circle shadow-sm">
                                    <i class="fas fa-clinic-medical fa-2x text-health"></i>
                                </div>
                            </div>
                            <div class="col-md">
                                <h5 class="mb-1 text-dark font-weight-bold">{{ $servicio->nombre }}</h5>
                                <p class="mb-0 text-muted small text-uppercase letter-spacing-1">
                                    <i class="fas fa-info-circle mr-1"></i> {{ $servicio->descripcion ?? 'Sin descripción disponible' }}
                                </p>
                            </div>
                            <div class="col-md-auto mt-3 mt-md-0">
                                <div class="text-right text-md-left border-left pl-md-4">
                                    <span class="d-block text-muted small font-weight-bold">CAPACIDAD ACTIVA</span>
                                    <span class="h4 font-weight-bold text-health">{{ count($servicio->usuarios) }}</span>
                                    <span class="text-muted small">Integrantes</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Formulario de Asignación con Estilo Moderno --}}
                    <div class="assignment-form-container bg-light p-4 rounded-lg mb-5 border">
                        <h6 class="text-muted text-uppercase font-weight-bold mb-4 small">
                            <i class="fas fa-plus-circle mr-1 text-health"></i> Nueva Asignación de Personal
                        </h6>
                        
                        <form action="{{ url('/servicio/' . $servicio->id . '/usuarioservicio') }}" method="POST">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                                    <label class="text-xs text-uppercase font-weight-bold mb-2">1. Seleccionar Profesional</label>
                                    <select name="usuario_id" id="usuario_id" class="form-control select2-buscar" required>
                                        <option value="">Buscar médico o empleado...</option>
                                        @foreach ($usuarios as $u)
                                            <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                    <label class="text-xs text-uppercase font-weight-bold mb-2">2. Cargo / Rol</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-briefcase text-muted"></i></span>
                                        </div>
                                        <input type="text" name="descripcion_usuario_servicio" class="form-control border-left-0 pl-0" placeholder="Ej: Especialista, Residente..." required>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-3 mb-lg-0">
                                    <label class="text-xs text-uppercase font-weight-bold mb-2">3. Estado</label>
                                    <select name="estado" class="form-control custom-select">
                                        <option value="Activo" selected>Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <button type="submit" class="btn btn-health btn-block shadow-sm">
                                        <i class="fas fa-user-plus mr-2"></i> Vincular al Servicio
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Tabla de Personal con Diseño Limpio --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-id-card-alt mr-2 text-muted"></i>Nómina del Personal
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover border">
                            <thead>
                                <tr>
                                    <th>Profesional</th>
                                    <th>Cargo / Especialidad</th>
                                    <th class="text-center">Estado</th>
                                    <th>Fecha de Ingreso</th>
                                    <th class="text-right">Gestión de Horarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($servicio->usuarios as $userAsignado)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm mr-3">
                                                    {{ strtoupper(substr($userAsignado->nombre, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <span class="d-block font-weight-bold text-dark">{{ $userAsignado->nombre }}</span>
                                                    <small class="text-muted">ID: #{{ $userAsignado->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-secondary font-weight-500">{{ $userAsignado->pivot->descripcion_usuario_servicio }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if(strtolower($userAsignado->pivot->estado) == 'activo')
                                                <span class="badge-status status-active">Activo</span>
                                            @else
                                                <span class="badge-status status-inactive">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-muted small">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ date('d M, Y', strtotime($userAsignado->pivot->fecha_ingreso)) }}
                                        </td>
                                        <td class="text-right align-middle">
                                            <a href="{{ route('turnos.create', ['usuario_id' => $userAsignado->id, 'servicio_id' => $servicio->id]) }}" 
                                               class="btn btn-sm btn-outline-health shadow-xs font-weight-bold px-3">
                                                <i class="fas fa-calendar-check mr-1"></i> Asignar Turno
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="empty-state">
                                                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="mb-3 opacity-50" alt="">
                                                <h6 class="text-muted">No hay personal registrado en este servicio todavía.</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-light border-top-0 p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-muted small">
                            <i class="fas fa-info-circle mr-1"></i> El personal vinculado podrá aparecer en la cuadrícula de turnos semanales.
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('servicio.index') }}" class="btn btn-link text-secondary font-weight-bold text-decoration-none">
                                <i class="fas fa-chevron-left mr-1"></i> Volver al listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
        --health-bg: #f0fdf4;
        --health-light: #dcfce7;
    }

    /* Estructura Principal */
    .card-health-outline { border-top: 6px solid var(--health-color) !important; border-radius: 16px !important; }
    .text-health { color: var(--health-color) !important; }
    .badge-health-light { background-color: var(--health-light); }
    .letter-spacing-1 { letter-spacing: 0.5px; }

    /* Banner Informativo */
    .info-banner { background: white; border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; border-left: 5px solid var(--health-color); }
    .service-icon-circle { width: 60px; height: 60px; background: var(--health-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; }

    /* Botones Modernos */
    .btn-health { background-color: var(--health-color); color: white; border-radius: 8px; font-weight: 700; transition: 0.3s; padding: 10px 20px; border: none; }
    .btn-health:hover { background-color: var(--health-hover); color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2); }
    .btn-outline-health { border: 2px solid var(--health-color); color: var(--health-color); border-radius: 8px; transition: 0.3s; }
    .btn-outline-health:hover { background: var(--health-color); color: white; }

    /* Avatar y Tabla */
    .avatar-sm { width: 40px; height: 40px; background: #e9ecef; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #495057; font-size: 0.8rem; border: 2px solid #dee2e6; }
    .table-custom thead th { background: #f8f9fa; border-top: none; color: #8898aa; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; padding: 15px; }
    .table-custom tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f4f8; }
    .table-hover tbody tr:hover { background-color: #fbfdfc; }

    /* Estados */
    .badge-status { padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; }
    .status-active { background-color: #dcfce7; color: #15803d; }
    .status-inactive { background-color: #fee2e2; color: #b91c1c; }

    /* Select2 Green Theme */
    .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] { background-color: var(--health-color) !important; }
    .select2-container--bootstrap4 .select2-selection--single { height: 45px !important; border-radius: 8px !important; border-color: #dee2e6 !important; }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { line-height: 45px !important; padding-left: 15px !important; }
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