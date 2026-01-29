@extends('adminlte::page')

@section('title', 'Gestión de Turnos')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Dashboard Hospitalario</h1>
        <div class="text-muted">Hoy: {{ now()->format('d/m/Y') }}</div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    
    {{-- 1. Cabecera de Bienvenida --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3 text-info"><i class="fas fa-user-md fa-2x"></i></div>
                        <div>
                            <h5 class="mb-0 text-bold">¡Bienvenido, {{ $usuario->persona->nombres ?? $usuario->nombre }}!</h5>
                            <small>Financiamiento: <span class="badge badge-secondary">{{ $usuario->persona->item ?? 'No asignada' }}</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Cuadros Estadísticos Rápidos --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success"><div class="inner"><h3>{{ $totalTGN }}</h3><p>TGN</p></div><div class="icon"><i class="fas fa-user-check"></i></div></div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info"><div class="inner"><h3>{{ $totalSUS }}</h3><p>SUS</p></div><div class="icon"><i class="fas fa-user-md"></i></div></div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning"><div class="inner"><h3>{{ $totalContrato }}</h3><p>Contratos</p></div><div class="icon"><i class="fas fa-file-signature"></i></div></div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-dark"><div class="inner"><h3>{{ $totalPersonal }}</h3><p>Total</p></div><div class="icon"><i class="fas fa-users"></i></div></div>
        </div>
    </div>

    {{-- Variables de Filtro --}}
    @php
        $srvId = request('servicio_id');
        $usrId = request('usuario_id');
        $semId = request('semana_id');
    @endphp

    {{-- 3. ASIGNACIÓN DE TURNOS --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title text-bold text-primary">
                        <i class="fas fa-calendar-alt mr-2"></i> Asignar Nuevo Turno
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('turnos.store') }}" method="POST">
                        @csrf
                        {{-- Inputs ocultos para mantener el estado del filtro al guardar --}}
                        <input type="hidden" name="servicio_id" value="{{ $srvId }}">
                        <input type="hidden" name="usuario_id" value="{{ $usrId }}">
                        <input type="hidden" name="semana_id" value="{{ $semId }}">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted"><small>1. SERVICIO HOSPITALARIO</small></label>
                                <select id="filt_srv" class="form-control select2 shadow-sm border-primary" onchange="actualizarFiltros()">
                                    <option value="">-- Seleccione Servicio --</option>
                                    @foreach($servicios as $ser)
                                        <option value="{{ $ser->id }}" {{ $srvId == $ser->id ? 'selected' : '' }}>{{ $ser->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted"><small>2. MÉDICO / PERSONAL</small></label>
                                <select id="filt_usr" class="form-control select2 shadow-sm border-success" onchange="actualizarFiltros()">
                                    <option value="">-- Seleccione Personal --</option>
                                    @foreach($usuarios as $u)
                                        <option value="{{ $u->id }}" {{ $usrId == $u->id ? 'selected' : '' }}>{{ $u->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="text-muted"><small>3. DÍA</small></label>
                                <select name="dia" class="form-control shadow-sm">
                                    @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $d)
                                        <option value="{{ $d }}">{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted"><small>4. HORARIO (CARGA HORARIA)</small></label>
                                <select name="turno_id" class="form-control shadow-sm border-primary" required>
                                    <option value="">-- Seleccione Turno --</option>
                                    @foreach($turnosDisponibles as $t)
                                        <option value="{{ $t->id_turno }}">
                                            {{ $t->nombre_turno }} ({{ $t->duracion_horas }}h)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted"><small>5. SEMANA</small></label>
                                <select id="filt_sem" class="form-control shadow-sm border-warning" onchange="actualizarFiltros()">
                                    <option value="">-- Seleccione semana --</option>
                                    @foreach($semanas as $sem)
                                        <option value="{{ $sem->id }}" {{ $semId == $sem->id ? 'selected' : '' }}>
                                            Semana {{ $sem->numero }} ({{ $sem->mes->nombre ?? 'Mes' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home') }}" class="btn btn-sm btn-link text-danger"><i class="fas fa-eraser"></i> Limpiar filtros</a>
                            <button type="submit" class="btn btn-success shadow-sm px-4">
                                <i class="fas fa-save mr-2"></i> <strong>Guardar Turno</strong>
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    {{-- 4. TABLA DE VISTA PREVIA SEMANAL --}}
                    <div class="table-responsive">
                        <h5 class="text-bold text-dark mb-3"><i class="fas fa-eye mr-2"></i> Vista Previa de la Semana</h5>
                        <table class="table table-bordered table-sm text-center shadow-sm">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="20%">Médico</th>
                                    @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                                        <th>{{ $dia }}</th>
                                    @endforeach
                                    <th class="bg-primary">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-light">
                                @forelse($usuarios as $u)
                                    <tr class="{{ $usrId == $u->id ? 'table-warning' : '' }}">
                                        <td class="text-left font-weight-bold px-2">
                                            <a href="?servicio_id={{ $srvId }}&usuario_id={{ $u->id }}&semana_id={{ $semId }}">
                                                {{ $u->nombre }}
                                            </a>
                                        </td>
                                        @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $d)
                                            <td>
                                                @php
                                                    $asig = $u->turnosAsignados
                                                        ->where('dia', $d)
                                                        ->where('semana_id', (int)$semId)
                                                        ->first();
                                                @endphp
                                                @if($asig)
                                                    <span class="badge badge-info">{{ $asig->turnoDetalle->nombre_turno ?? 'T' }}</span>
                                                @else
                                                    <span class="text-muted" style="opacity: 0.3;">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-primary font-weight-bold">
                                            {{ $u->turnosAsignados->where('semana_id', (int)$semId)->sum(fn($a) => $a->turnoDetalle->duracion_horas ?? 0) }}h
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9">No hay personal para mostrar</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function actualizarFiltros() {
    const srv = document.getElementById('filt_srv').value;
    const usr = document.getElementById('filt_usr').value;
    const sem = document.getElementById('filt_sem').value;
    
    let url = new URL(window.location.href);
    if (srv) url.searchParams.set('servicio_id', srv); else url.searchParams.delete('servicio_id');
    if (usr) url.searchParams.set('usuario_id', usr); else url.searchParams.delete('usuario_id');
    if (sem) url.searchParams.set('semana_id', sem); else url.searchParams.delete('semana_id');
    
    window.location.href = url.toString();
}
</script>
@stop