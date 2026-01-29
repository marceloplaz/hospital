@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            {{-- Alertas --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i> Asignar Nuevo Turno</h5>
                    <span class="badge bg-light text-primary">Paso 1: Filtrar | Paso 2: Asignar</span>
                </div>

                <div class="card-body">
                    @php
                        $srvId = request('servicio_id');
                        $usrId = request('usuario_id');
                        $semId = request('semana_id');
                    @endphp

                    <form action="{{ route('turnos.store') }}" method="POST">
                        @csrf
                        
                        {{-- IDs ocultos para el controlador --}}
                        <input type="hidden" name="servicio_id" value="{{ $srvId }}">
                        <input type="hidden" name="usuario_id" value="{{ $usrId }}">
                        <input type="hidden" name="semana_id" value="{{ $semId }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">1. Servicio Hospitalario</label>
                                <select id="filt_srv" class="form-select border-primary" onchange="actualizarFiltros()">
                                    <option value="">-- Seleccione el servicio --</option>
                                    @foreach($servicios as $ser)
                                        <option value="{{ $ser->id }}" {{ $srvId == $ser->id ? 'selected' : '' }}>{{ $ser->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">2. Médico / Personal</label>
                                <select id="filt_usr" class="form-select {{ $usrId ? 'is-valid' : '' }}" onchange="actualizarFiltros()">
                                    <option value="">-- Seleccione un usuario --</option>
                                    @foreach($usuarios as $user)
                                        <option value="{{ $user->id }}" {{ $usrId == $user->id ? 'selected' : '' }}>{{ $user->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row bg-light p-3 rounded-3 mb-3 mx-1 border shadow-sm">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-primary">3. Día</label>
                                <select name="dia" class="form-select border-primary">
                                    @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $d)
                                        <option value="{{ $d }}" {{ old('dia') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>
<div class="col-md-4">
    <label class="text-muted"><small>4. HORARIO (CARGA HORARIA)</small></label>
    <select name="turno_id" class="form-control shadow-sm border-primary" required>
        <option value="">-- Seleccione Turno --</option>
        @foreach($turnosDisponibles as $t)
            @php
                // Intentamos obtener los valores de las columnas exactas de tu imagen
                $nombre = $t->nombre_turno ?? $t->nombre ?? 'Turno s/n';
                $horas  = $t->duracion_horas ?? $t->horas ?? '0';
            @endphp
            <option value="{{ $t->id_turno ?? $t->id }}">
                {{ $nombre }} ({{ $horas }}h)
            </option>
        @endforeach
    </select>
</div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-primary">5. Semana</label>
                                <select id="filt_sem" class="form-select border-primary" onchange="actualizarFiltros()">
                                    <option value="">-- Seleccione semana --</option>
                                    @foreach($semanas as $sem)
                                        <option value="{{ $sem->id }}" {{ $semId == $sem->id ? 'selected' : '' }}>
                                            Sem. {{ $sem->numero }} ({{ $sem->mes->nombre ?? 'Mes' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('turnos.create') }}" class="btn btn-outline-secondary btn-sm">Limpiar filtros</a>
                            <button type="submit" class="btn btn-success px-5 shadow fw-bold">
                                <i class="fas fa-save me-2"></i> Guardar Turno
                            </button>
                        </div>
                    </form>

                    <hr class="my-5">

                    <h4 class="mb-3 text-primary"><i class="fas fa-table me-2"></i>Vista Previa: Sem. {{ $semanas->where('id', $semId)->first()->numero ?? '' }}</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center align-middle shadow-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>Médico</th>
                                    @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                                        <th>{{ $dia }}</th>
                                    @endforeach
                                    <th class="bg-primary">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $u)
                                    <tr class="{{ $usrId == $u->id ? 'table-warning' : '' }}">
                                        <td class="text-start ps-3 fw-bold">
                                            <a href="?servicio_id={{ $srvId }}&usuario_id={{ $u->id }}&semana_id={{ $semId }}" class="text-decoration-none">
                                                {{ $u->nombre }}
                                            </a>
                                        </td>
                                        @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $d)
                                            <td>
                                                @php
                                                    // Buscamos la asignación forzando que los IDs sean enteros para la comparación
                                                    $asig = $u->turnosAsignados
                                                        ->where('dia', $d)
                                                        ->where('semana_id', (int)$semId)
                                                        ->first();
                                                @endphp
                                                @if($asig && $asig->turnoDetalle)
                                                    <span class="badge bg-info text-dark shadow-sm">
                                                        {{ $asig->turnoDetalle->tipo ?? $asig->turnoDetalle->nombre ?? 'SI' }}
                                                    </span>
                                                @else
                                                    <span class="text-muted" style="opacity: 0.3;">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="fw-bold text-primary bg-light">
                                            @php
                                                // Suma de horas buscando el nombre de columna correcto
                                                $totalHoras = $u->turnosAsignados
                                                    ->where('semana_id', (int)$semId)
                                                    ->sum(function($a) {
                                                        return $a->turnoDetalle->duracion ?? $a->turnoDetalle->horas ?? 0;
                                                    });
                                            @endphp
                                            {{ $totalHoras }}h
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-4 text-muted">No hay personal para mostrar. Seleccione un servicio.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function actualizarFiltros() {
    const srv = document.getElementById('filt_srv').value;
    const usr = document.getElementById('filt_usr').value;
    const sem = document.getElementById('filt_sem').value;
    
    let url = new URL(window.location.origin + window.location.pathname);
    if (srv) url.searchParams.set('servicio_id', srv);
    if (usr) url.searchParams.set('usuario_id', usr);
    if (sem) url.searchParams.set('semana_id', sem);
    
    window.location.href = url.toString();
}
</script>
@endsection