@extends('adminlte::page')

@section('title', 'Editar Turno')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-health-outline shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title text-health text-bold text-uppercase">
                        <i class="fas fa-edit mr-2"></i> Modificar Asignación
                    </h3>
                </div>

                <form action="{{ route('turnos.update', $asignacion->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger shadow-sm border-0">
                                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        {{-- Información fija del registro --}}
                        <div class="bg-health-soft p-3 rounded mb-4 border-dashed">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block text-uppercase">Médico</small>
                                    <span class="font-weight-bold"><i class="fas fa-user-md text-health mr-1"></i> {{ $asignacion->usuario->nombre }}</span>
                                </div>
                                <div class="col-6 text-right">
                                    <small class="text-muted d-block text-uppercase">Periodo</small>
                                    <span class="badge bg-health text-white">Semana {{ $asignacion->semana->numero }} - {{ $asignacion->semana->mes->nombre }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-muted text-xs text-uppercase font-weight-bold">Seleccionar Nuevo Día</label>
                            <select name="dia" class="form-control border-health shadow-sm" required>
                                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $d)
                                    <option value="{{ $d }}" {{ $asignacion->dia == $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="text-muted text-xs text-uppercase font-weight-bold">Seleccionar Nuevo Horario</label>
                            <select name="turno_id" class="form-control border-health shadow-sm" required>
                                @foreach($turnosDisponibles as $t)
                                    <option value="{{ $t->id_turno }}" {{ $asignacion->turno_id == $t->id_turno ? 'selected' : '' }}>
                                        {{ $t->nombre_turno }} ({{ $t->duracion_horas }}h)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-warning mt-4 border-0 shadow-xs">
                            <small><i class="fas fa-info-circle mr-1"></i> 
                                <b>Aviso:</b> El sistema no permitirá guardar si el médico ya tiene este mismo turno asignado en el día seleccionado.
                            </small>
                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-between py-3">
                        <a href="{{ route('turnos.create', ['servicio_id' => $asignacion->servicio_id, 'semana_id' => $asignacion->semana_id]) }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-health rounded-pill px-5 shadow-sm font-weight-bold">
                            <i class="fas fa-save mr-2"></i> ACTUALIZAR TURNO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    :root { --health-green: #28a745; --health-soft: #f0fff4; }
    .text-health { color: var(--health-green) !important; }
    .bg-health { background-color: var(--health-green) !important; }
    .bg-health-soft { background-color: var(--health-soft) !important; }
    .border-health { border: 1px solid var(--health-green) !important; }
    .card-health-outline { border-top: 5px solid var(--health-green); border-radius: 12px; }
    .btn-health { background-color: var(--health-green); color: white; border: none; }
    .btn-health:hover { background-color: #218838; color: white; transform: translateY(-1px); }
    .border-dashed { border: 1px dashed var(--health-green) !important; }
</style>
@stop