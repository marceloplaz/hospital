@extends('adminlte::page')

@section('title', 'Gestión de Servicios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark font-weight-bold">
            <i class="fas fa-layer-group text-success mr-2"></i>Gestión de Servicios
        </h1>
        <button class="btn btn-success shadow-sm rounded-pill px-4" data-toggle="collapse" data-target="#collapseRegistro">
            <i class="fas fa-plus-circle mr-1"></i> Nuevo Servicio
        </button>
    </div>
@stop

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 5px solid #1e7e34 !important;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- 1. FORMULARIO DE REGISTRO (Ahora colapsable para limpieza visual) --}}
    <div class="collapse {{ $errors->any() ? 'show' : '' }} mb-4" id="collapseRegistro">
        <div class="card border-0 shadow-lg" style="border-radius: 15px;">
            <div class="card-body">
                <form action="{{ route('servicio.store') }}" method="post">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="text-muted small font-weight-bold text-uppercase">Nombre del Servicio</label>
                            <input type="text" name="nombre" class="form-control shadow-none border-faded" placeholder="Ej: Pediatría" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small font-weight-bold text-uppercase">Descripción del Área</label>
                            <input type="text" name="descripcion" class="form-control shadow-none border-faded" placeholder="Breve descripción..." required>
                        </div>
                        <div class="col-md-2">
                            <label class="text-muted small font-weight-bold text-uppercase">Cap. Pacientes</label>
                            <input type="number" name="cantidad_pacientes" class="form-control shadow-none border-faded" placeholder="0" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> GUARDAR UNIDAD
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. TABLA DE SERVICIOS --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tabla-servicios" class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3" style="width: 80px">ID</th>
                            <th class="border-0 py-3">SERVICIO</th>
                            <th class="border-0 py-3">DESCRIPCIÓN</th>
                            <th class="border-0 py-3 text-center">CAPACIDAD</th>
                            <th class="border-0 py-3 text-right pr-4">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicio as $s)
                        <tr>
                            <td class="px-4 align-middle">
                                <span class="badge badge-soft-secondary">#{{ $s->id }}</span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="icon-service-avatar mr-2">
                                        {{ substr($s->nombre, 0, 1) }}
                                    </div>
                                    <span class="font-weight-bold text-dark">{{ $s->nombre }}</span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted small font-weight-500">{{ $s->descripcion }}</span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="capacity-indicator">
                                    <i class="fas fa-users-cog mr-1 opacity-5"></i> {{ $s->cantidad_pacientes }}
                                </div>
                            </td>
                            <td class="text-right align-middle pr-4">
                                <div class="btn-group action-buttons">
                                    <a href="{{ route('servicio.show', $s->id) }}" class="btn btn-link text-info" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('servicio.edit', $s->id) }}" class="btn btn-link text-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ url('/servicio/' . $s->id . '/usuarioservicio') }}" class="btn btn-link text-primary" title="Asignar Usuario">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                    <form action="{{ route('servicio.destroy', $s->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Estilos de Elegancia */
    .border-faded { border: 1px solid #e2e8f0; border-radius: 8px; }
    .card { border-radius: 15px !important; }
    
    /* Avatares de Servicio */
    .icon-service-avatar {
        width: 32px; height: 32px; background: #eafaf1; color: #28a745;
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.8rem; border: 1px solid #d4edda;
    }

    /* Badges y Pill Indicators */
    .badge-soft-secondary { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .capacity-indicator {
        display: inline-block; padding: 4px 12px; background: #f0fdf4;
        color: #166534; border-radius: 20px; font-size: 0.85rem; font-weight: 600;
    }

    /* DataTables Custom */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #28a745 !important; color: white !important; border: none !important; border-radius: 5px;
    }
    
    /* Botones de acción sin bordes toscos */
    .action-buttons .btn-link {
        font-size: 1.1rem; padding: 0.25rem 0.5rem; transition: transform 0.2s;
    }
    .action-buttons .btn-link:hover { transform: scale(1.2); }

    /* Ajuste de encabezado de tabla */
    thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; }
</style>
@stop

@push('js')
<script>
    $(document).ready(function() {
        $('#tabla-servicios').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "responsive": true, 
            "autoWidth": false,
            "order": [[ 0, "desc" ]],
            "dom": '<"row px-4 pt-3"<"col-md-6"l><"col-md-6"f>>rt<"row px-4 pb-3"<"col-md-6"i><"col-md-6"p>>',
        });
    });
</script>
@endpush