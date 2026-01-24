@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11"> {{-- Aumenté un poco el ancho para que la tabla respire mejor --}}
            {{-- Alertas de éxito o error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
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
                        // FORZAMOS A ENTERO: Esto es clave para que el ->where() funcione
                        $srvId = request('servicio_id') ? (int)request('servicio_id') : null;
                        $usrId = request('usuario_id') ? (int)request('usuario_id') : null;
                        $semId = request('semana_id') ? (int)request('semana_id') : null;
                    @endphp
                    

                    <form action="{{ route('turnos.store') }}" method="POST">
                        @csrf
                        {{-- Campos ocultos para mantener el estado tras el envío --}}
                        <input type="hidden" name="redirect_params" value="{{ http_build_query(request()->all()) }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">1. Servicio Hospitalario</label>
                                <select name="servicio_id" id="servicio_id" class="form-select border-primary" onchange="actualizarFiltros()">
                                    <option value="">-- Seleccione el servicio --</option>
                                    @foreach($servicios as $ser)
                                        <option value="{{ $ser->id }}" {{ $srvId == $ser->id ? 'selected' : '' }}>
                                            {{ $ser->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">2. Médico / Personal</label>
                                <select name="usuario_id" id="usuario_id" class="form-select {{ $usrId ? 'is-valid' : '' }} @error('usuario_id') is-invalid @enderror" onchange="actualizarFiltros()">
                                    <option value="">-- Seleccione un usuario --</option>
                                    @foreach($usuarios as $user)
                                        <option value="{{ $user->id }}" {{ ($usrId == $user->id || old('usuario_id') == $user->id) ? 'selected' : '' }}>
                                            {{ $user->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('usuario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row bg-light p-3 rounded-3 mb-3 mx-1 border">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">3. Día</label>
                                <select name="dia" class="form-select">
                                    @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $d)
                                        <option value="{{ $d }}" {{ old('dia') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">4. Horario</label>
                                <select name="turno_id" class="form-select">
                                    @foreach($turnosDisponibles as $t)
                                        <option value="{{ $t->id }}" {{ old('turno_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->tipo }} ({{ $t->duracion }}h)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">5. Semana</label>
                                <select name="semana_id" id="semana_id" class="form-select border-primary" onchange="actualizarFiltros()">
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
                            <a href="{{ route('turnos.create') }}" class="btn btn-link text-muted">Limpiar selección</a>
                            <button type="submit" class="btn btn-success px-5 shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i> Guardar Turno
                            </button>
                        </div>
                    </form>

                    <hr class="my-5">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0 text-primary"><i class="fas fa-table me-2"></i>Vista Previa de la Semana</h4>
                        <div class="small text-muted">Haga clic en un nombre para seleccionarlo</div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center align-middle shadow-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th width="20%">Médico</th>
                                    @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $diaAbbr)
                                        <th>{{ $diaAbbr }}</th>
                                    @endforeach
                                    <th class="bg-primary">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $personal = $srvId ? $usuarios : []; @endphp

                                @forelse($personal as $u)
                                    <tr class="{{ $usrId == $u->id ? 'table-warning border-warning' : '' }}">
                                        <td class="text-start ps-3">
                                            <a href="?servicio_id={{ $srvId }}&usuario_id={{ $u->id }}&semana_id={{ $semId }}" 
                                               class="text-decoration-none fw-bold {{ $usrId == $u->id ? 'text-dark' : 'text-primary' }}">
                                                <i class="{{ $usrId == $u->id ? 'fas fa-user-edit' : 'far fa-user' }} me-1"></i>
                                                {{ $u->nombre }}
                                            </a>
                                        </td>
                                        @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $d)
                                            @php
                                                $misTurnos = $u->turnosAsignados ?? collect();
                                                // El uso de (int) aquí es vital para la comparación
                                                $asig = $misTurnos->where('dia', $d)
                                                                 ->where('semana_id', $semId)
                                                                 ->where('servicio_id', $srvId)
                                                                 ->first();
                                            @endphp
                                            <td>
                                                @if($asig && $asig->turnoDetalle)
                                                    <span class="badge bg-info text-dark shadow-sm" style="font-size: 0.75rem;">
                                                        {{ $asig->turnoDetalle->tipo }}
                                                    </span>
                                                @else
                                                    <span class="text-muted" style="opacity: 0.3;">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="fw-bold text-primary bg-light">
                                            @php
                                                // Sumamos solo los turnos que coincidan con los filtros actuales
                                                $horas = $u->turnosAsignados ? $u->turnosAsignados
                                                    ->where('semana_id', $semId)
                                                    ->where('servicio_id', $srvId)
                                                    ->sum(fn($a) => $a->turnoDetalle->duracion ?? 0) : 0;
                                            @endphp
                                            {{ $horas }}h
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-5 text-muted bg-light">
                                            <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                                            Debe seleccionar un <strong>Servicio</strong> y una <strong>Semana</strong> para visualizar el cronograma.
                                        </td>
                                    </tr>
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
    const srv = document.getElementById('servicio_id').value;
    const sem = document.getElementById('semana_id').value;
    const usr = document.getElementById('usuario_id').value;
    
    let params = new URLSearchParams();
    if (srv) params.append('servicio_id', srv);
    if (sem) params.append('semana_id', sem);
    if (usr) params.append('usuario_id', usr);
    
    window.location.href = window.location.pathname + '?' + params.toString();
}
</script>
@endsection