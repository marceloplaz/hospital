@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold"><i class="fas fa-users-cog"></i> Gestión de Personal</h2>
        <a href="{{ route('personas.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus-circle"></i> Registrar Nuevo
        </a>
    </div>

    {{-- Mensajes de Notificación --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-start border-4 border-success" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filtros Avanzados --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body bg-light rounded">
            <form action="{{ route('personas.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted">Buscar por Apellido</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="apellido" class="form-control" placeholder="Escriba el apellido..." value="{{ request('apellido') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Filtrar por Ítem</label>
                    <select name="item" class="form-select">
                        <option value="">-- Todos los Ítems --</option>
                        <option value="Item TGN" {{ request('item') == 'Item TGN' ? 'selected' : '' }}>Item TGN</option>
                        <option value="Item SUS" {{ request('item') == 'Item SUS' ? 'selected' : '' }}>Item SUS</option>
                        <option value="Contrato" {{ request('item') == 'Contrato' ? 'selected' : '' }}>Contrato</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    <a href="{{ route('personas.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Personal --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-3">Personal</th>
                        <th class="text-center">Cargo / Rol</th>
                        <th class="text-center">Financiamiento</th>
                        <th>Usuario Asignado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personas as $persona)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold">{{ $persona->nombres }}</div>
                            <div class="small text-muted">{{ $persona->apellidos }}</div>
                        </td>
                        <td class="text-center">
                            @php
                                $roleBadge = match($persona->tipo_trabajador) {
                                    'SuperAdmin'    => 'bg-danger',
                                    'Médico'        => 'bg-primary',
                                    'Enfermero', 'Enfermera' => 'bg-info text-dark',
                                    'Administrativo' => 'bg-warning text-dark',
                                    'Personal'      => 'bg-secondary',
                                    'Chofer'        => 'bg-dark',
                                    default         => 'bg-light text-dark border'
                                };
                            @endphp
                            <span class="badge {{ $roleBadge }} px-3 py-2 rounded-pill shadow-sm">
                                <i class="fas fa-briefcase me-1 small"></i> {{ $persona->tipo_trabajador }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $itemClass = match($persona->item) {
                                    'Item TGN' => 'text-success border-success',
                                    'Item SUS' => 'text-info border-info',
                                    'Contrato' => 'text-warning border-warning',
                                    default    => 'text-muted border-secondary'
                                };
                            @endphp
                            <span class="badge border {{ $itemClass }} fw-normal">
                                {{ $persona->item }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle fa-2x text-muted me-2"></i>
                                <span class="small fw-bold">{{ $persona->usuario->nombre ?? 'Sin usuario' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                {{-- Botón Ver Detalle --}}
                                <a href="{{ route('personas.show', $persona->id) }}" class="btn btn-white btn-sm border" title="Ver Detalle">
                                    <i class="fas fa-eye text-success"></i>
                                </a>

                                {{-- Botón Editar --}}
                                <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-white btn-sm border" title="Editar">
                                    <i class="fas fa-edit text-info"></i>
                                </a>
                                
                                {{-- Botón Eliminar: Protegido por Gate --}}
                                @can('admin-only')
                                <form action="{{ route('personas.destroy', $persona->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar? Se borrará también su cuenta.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-white btn-sm border" title="Eliminar">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <p>No se encontró personal registrado.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table thead th { font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge { font-size: 0.75rem; }
    .btn-white { background: #fff; border-color: #dee2e6; }
    .btn-white:hover { background: #f8f9fa; color: inherit; }
</style>
@endsection