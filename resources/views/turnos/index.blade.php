@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Panel de Control de Turnos Semanales</h2>
            <p class="text-primary fw-bold mt-1">
                {{ $servicioSeleccionado->nombre ?? 'Todos los Servicios' }} 
                <span class="text-muted mx-2">|</span> 
                @if($semanaSeleccionada)
                    Semana {{ $semanaSeleccionada->numero }} ({{ $semanaSeleccionada->mes->nombre }})
                @else
                    Todas las Semanas
                @endif
            </p>
        </div>
        <a href="{{ route('turnos.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Asignar Nuevo Turno
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body bg-light rounded">
            <form action="{{ route('turnos.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-uppercase text-muted">Servicio Hospitalario</label>
                    <select name="servicio_id" class="form-select border-0 shadow-sm">
                        <option value="">-- Ver todos --</option>
                        @foreach($servicios as $ser)
                            <option value="{{ $ser->id }}" {{ request('servicio_id') == $ser->id ? 'selected' : '' }}>
                                {{ $ser->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-uppercase text-muted">Semana (Calendario)</label>
                    <select name="semana_id" class="form-select border-0 shadow-sm">
                        <option value="">-- Ver todas --</option>
                        @foreach($semanas as $sem)
                            <option value="{{ $sem->id }}" {{ request('semana_id') == $sem->id ? 'selected' : '' }}>
                                Semana {{ $sem->numero }} - {{ $sem->mes->nombre }} ({{ $sem->fecha_inicio }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-secondary px-4 shadow-sm">
                        <i class="bi bi-filter me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('turnos.index') }}" class="btn btn-outline-dark px-4 ms-1">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 text-dark"><i class="bi bi-calendar3 me-2 text-primary"></i>Distribución de Horarios</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 200px;">Médico / Personal</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miércoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                            <th>Sábado</th>
                            <th>Domingo</th>
                            <th class="bg-primary border-primary">Total Horas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turnos as $usuario)
                            @php $totalHorasSemana = 0; @endphp
                            <tr>
                                <td class="fw-bold bg-light ps-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle me-2 text-secondary"></i>
                                        {{ $usuario->nombre }}
                                    </div>
                                </td>
                                
                                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                    @php
                                        $asignacion = $usuario->turnosAsignados->where('dia', $dia)->first();
                                        $duracion = $asignacion->turnoDetalle->duracion ?? 0;
                                        $totalHorasSemana += $duracion;
                                    @endphp
                                    
                                    <td class="text-center" style="min-width: 110px;">
                                        @if($asignacion)
                                            <div class="py-2">
                                                <span class="badge rounded-pill bg-info text-dark shadow-sm px-3">
                                                    {{ $asignacion->turnoDetalle->tipo }}
                                                </span>
                                                <div class="fw-bold mt-1 small">{{ $duracion }} hrs</div>
                                                <div class="mt-2 action-buttons">
                                                    <a href="#" class="text-warning text-decoration-none me-2" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                                    <form action="#" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-link text-danger p-0 border-0" onclick="return confirm('¿Eliminar turno?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-light fs-4">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="text-center fw-bold bg-light text-primary fs-4 border-start-0">
                                    {{ $totalHorasSemana }}h
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($turnos->isEmpty())
                <div class="text-center py-5 bg-light rounded mt-3">
                    <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted fs-5">No se encontraron turnos con los filtros seleccionados.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th { font-weight: 600; font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase; }
    .badge { font-weight: 500; }
    .action-buttons { opacity: 0; transition: opacity 0.3s; }
    tr:hover .action-buttons { opacity: 1; }
    .table-bordered td, .table-bordered th { border-color: #dee2e6; }
</style>
@endsection