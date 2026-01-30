@extends('adminlte::page')

@section('title', 'Gestión de Servicios')

@section('content_header')
    <h1><i class="fas fa-hospital-symbol mr-2"></i>Gestión de Servicios - Hospital</h1>
@stop

@section('content')
<div class="container-fluid">
    
    {{-- Mensajes de Éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- 1. FORMULARIO DE REGISTRO --}}
    <div class="card card-primary card-outline shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Registrar Nuevo Servicio</h3>
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('servicio.store') }}" method="post">
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
                        <input type="number" name="cantidad_pacientes" class="form-control" placeholder="0" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> GUARDAR SERVICIO
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. TABLA DE SERVICIOS (Con ID para DataTable) --}}
    <div class="card shadow-sm border-top-0">
        <div class="card-body p-3"> {{-- Un poco de padding para que el buscador respire --}}
            <table id="tabla-servicios" class="table table-hover table-striped mb-0">
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

                                <a href="{{ route('servicio.edit', $s->id) }}" class="btn btn-warning btn-sm shadow-sm ml-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ url('/servicio/' . $s->id . '/usuarioservicio') }}" class="btn btn-primary btn-sm shadow-sm ml-1" title="Asignar Usuario">
                                    <i class="fas fa-user-plus"></i>
                                </a>

                                <form action="{{ route('servicio.destroy', $s->id) }}" method="POST" class="ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                        <i class="fas fa-trash"></i>
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
@stop

{{-- 3. ACTIVACIÓN DE DATATABLES --}}
@push('js')
<script>
    $(document).ready(function() {
        $('#tabla-servicios').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "responsive": true, 
            "autoWidth": false,
            "order": [[ 0, "desc" ]], // Ordena por el ID de forma descendente
            "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>', // Acomoda buscador y paginación
        });
    });
</script>
@endpush