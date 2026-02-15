@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark font-weight-bold" style="font-size: 1.5rem;">
            <i class="fas fa-th-large mr-2 text-gold"></i>Panel de Control
        </h1>
        <div class="d-flex align-items-center">
            <span class="badge badge-gold-soft px-3 py-1 rounded-pill shadow-xs">
                <i class="far fa-calendar-alt mr-1"></i> {{ date('d/m/Y') }}
            </span>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    
    <div class="row">
        {{-- Tarjeta: Servicios --}}
        <div class="col-lg-3 col-6">
            <div class="small-box custom-card shadow-sm border-0">
                <div class="inner">
                    <h3 class="text-gold" style="font-size: 1.8rem;">{{ $usuario->servicio ? $usuario->servicio->count() : 0 }}</h3>
                    <p class="text-muted font-weight-bold mb-0" style="font-size: 0.9rem;">Servicios</p>
                </div>
                <div class="icon"><i class="fas fa-hospital-user text-gold-faded"></i></div>
            </div>
        </div>

        {{-- Tarjeta: Turno de Hoy --}}
        <div class="col-lg-3 col-6">
            <div class="small-box custom-card shadow-sm border-0 border-gold-left">
                <div class="inner">
{{-- Tarjeta: Turno de Hoy --}}
<div class="col-lg-3 col-6">
    <div class="small-box custom-card shadow-sm border-0 border-gold-left">
        <div class="inner">
            @php 
                $diaHoyBusqueda = str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], strtolower($hoyNombreEs));
                // Obtenemos la colección de turnos de hoy
                $turnosDeHoy = $misTurnos->get($diaHoyBusqueda);
            @endphp
            
            @if($turnosDeHoy && $turnosDeHoy->count() > 0)
                {{-- Si hay más de un servicio hoy, mostramos el primero o un resumen --}}
                @foreach($turnosDeHoy as $turno)
                    <h3 class="text-dark mb-0" style="font-size: 1rem;">
                        {{ $turno->turnoDetalle->nombre_turno }}
                    </h3>
                    <p class="text-gold font-weight-bold mb-1" style="font-size: 0.8rem;">
                        <i class="fas fa-hospital-alt mr-1"></i>{{ $turno->servicio->nombre ?? 'S/S' }}
                    </p>
                @endforeach
            @else
                <h3 class="text-muted" style="font-size: 1.2rem;">Libre</h3>
                <p class="text-gold font-weight-bold mb-0" style="font-size: 0.9rem;">—</p>
            @endif
            <small class="text-muted font-italic" style="font-size: 0.75rem;">Hoy: {{ $hoyNombreEs }}</small>
        </div>
        <div class="icon"><i class="fas fa-clock text-gold-faded"></i></div>
    </div>
</div>
                    <small class="text-muted font-italic" style="font-size: 0.75rem;">Hoy: {{ $hoyNombreEs }}</small>
                </div>
                <div class="icon"><i class="fas fa-clock text-gold-faded"></i></div>
            </div>
        </div>

        {{-- Tarjeta: Estado --}}
        <div class="col-lg-3 col-6">
            <div class="small-box custom-card shadow-sm border-0">
                <div class="inner">
                    <h3 class="text-success" style="font-size: 1.8rem;">{{ $usuario->estado == 1 ? 'Activo' : 'Inactivo' }}</h3>
                    <p class="text-muted font-weight-bold mb-0" style="font-size: 0.9rem;">Estado</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle text-success-faded"></i></div>
            </div>
        </div>

{{-- Tarjeta: Semana Actual --}}
<div class="col-lg-3 col-6">
    <div class="small-box custom-card shadow-sm border-0">
        <div class="inner">
            <h3 class="text-info" style="font-size: 1.2rem;">{{ $semanaActual->nombre ?? 'Sin Semana' }}</h3>
            
            @if($semanaActual)
                <p class="text-muted mb-0" style="font-size: 0.8rem;">
                    <i class="fas fa-info-circle mr-1"></i> Rol vigente
                </p>
                <small class="text-info font-weight-bold">PDF disponible abajo ↓</small>
            @else
                <p class="text-muted font-weight-bold mb-0" style="font-size: 0.8rem;">Sin programar</p>
            @endif
        </div>
        <div class="icon"><i class="fas fa-calendar-alt text-info-faded"></i></div>
    </div>
</div>

    </div>

    <div class="row mt-1">
        {{-- Horario Semanal --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm card-luxury h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 px-3 pb-0">
                    <h6 class="card-title font-weight-bold">
                        <i class="fas fa-calendar-week mr-2 text-gold"></i>Mi Horario Semanal
                    </h6>
                </div>
                <div class="card-body pt-2">
                    <ul class="list-group list-group-flush">
                        @php $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']; @endphp
                        @foreach($dias as $dia)
    @php 
        $diaLimpio = str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], strtolower($dia));
        $turnosDelDia = $misTurnos->get($diaLimpio); 
    @endphp
    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-1" style="font-size: 0.9rem;">
        <span class="font-weight-bold {{ $hoyNombreEs == $dia ? 'text-gold' : 'text-muted' }}">
            {{ $dia }}
            @if($hoyNombreEs == $dia) <small class="ml-1 badge badge-gold-soft" style="font-size: 10px;">HOY</small> @endif
        </span>

        <div class="text-right">
            @if($turnosDelDia)
                @foreach($turnosDelDia as $t)
                    <div class="mb-1">
                        <span class="badge badge-gold-soft px-2 py-1">
                            <small class="d-block font-weight-bold" style="font-size: 0.65rem; color: #aa822d;">
                                {{ strtoupper($t->servicio->nombre ?? 'Servicio') }}
                            </small>
                            {{ $t->turnoDetalle->nombre_turno }} 
                            <small class="d-block" style="font-size: 0.7rem;">
                                {{ \Carbon\Carbon::parse($t->turnoDetalle->hora_inicio)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($t->turnoDetalle->hora_fin)->format('H:i') }}
                            </small>
                        </span>
                    </div>
                @endforeach
            @else
                <span class="badge badge-light text-muted px-2 py-1" style="opacity: 0.6; font-size: 0.75rem;">Libre</span>
            @endif
        </div>
    </li>
@endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Servicios --}}
<div class="col-md-7">
    <div class="card border-0 shadow-sm card-luxury h-100">
        <div class="card-header bg-white border-bottom-0 pt-3 px-3 pb-0">
            <h6 class="card-title font-weight-bold">
                <i class="fas fa-clipboard-list mr-2 text-gold"></i>Servicios Asignados
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-light-gold">
                        <tr>
                            <th class="px-3 py-2 text-gold-dark">SERVICIO</th>
                            <th class="px-3 py-2 text-gold-dark text-right">ACCIONES</th> {{-- Nueva columna --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuario->servicio as $servicio)
                        <tr>
                            <td class="px-3 py-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-service mr-2" style="width:28px; height:28px; font-size:0.8rem;">{{ substr($servicio->nombre, 0, 1) }}</div>
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $servicio->nombre }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $servicio->pivot->descripcion_usuario_servicio ?? 'Activo' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-right"> {{-- Botón dinámico --}}
                                @if($semanaActual)
                                    <a href="{{ url('turnos/pdf') }}?servicio_id={{ $servicio->id }}&anio={{ date('Y') }}&mes_id={{ date('n') }}&semana_id={{ $semanaActual->id }}" 
                                       target="_blank" 
                                       class="btn btn-xs btn-outline-danger rounded-pill px-2 shadow-sm" 
                                       title="Ver Rol de {{ $servicio->nombre }}">
                                        <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center py-2 text-muted">Sin servicios.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 text-center py-2">
            <a href="{{ url('perfil') }}" class="btn btn-outline-gold btn-xs rounded-pill px-3">Ver perfil</a>
        </div>
    </div>
</div>

    </div>
</div>
@stop

@section('css')
<style>
    :root {
        --gold-primary: #d4af37;
        --gold-dark: #aa822d;
        --gold-soft: rgba(212, 175, 55, 0.1);
        --gold-gradient: linear-gradient(135deg, #d4af37 0%, #aa822d 100%);
    }
    .text-gold { color: var(--gold-primary) !important; }
    .text-gold-dark { color: var(--gold-dark) !important; font-size: 0.8rem; letter-spacing: 0.5px; }
    .bg-light-gold { background-color: #fcfaf2; }
    .card-luxury { border-radius: 15px !important; overflow: hidden; border: 1px solid rgba(0,0,0,0.03) !important; }
    
    /* TARJETAS MÁS CHICAS */
    .custom-card { border-radius: 10px !important; background: white; padding: 10px; transition: transform 0.3s ease; height: 100px; position: relative; }
    .custom-card:hover { transform: translateY(-3px); }
    
    .badge-gold-soft { background: var(--gold-soft); color: var(--gold-dark); border: 1px solid rgba(212, 175, 55, 0.2); font-weight: 600; }
    .badge-success-soft { background: #e6f7ef; color: #1e7e34; font-weight: 600; border-radius: 6px; }
    .badge-danger-soft { background: #fdecea; color: #dc3545; font-weight: 600; border-radius: 6px; }
    .border-gold-left { border-left: 4px solid var(--gold-primary) !important; }
    .avatar-service {
        background: var(--gold-gradient);
        color: white; border-radius: 8px; display: flex; align-items: center;
        justify-content: center; font-weight: bold;
    }
    .btn-outline-gold { border-color: var(--gold-primary); color: var(--gold-primary); font-weight: 600; }
    .btn-outline-gold:hover { background: var(--gold-gradient); color: white; }
    .text-gold-faded { color: rgba(212, 175, 55, 0.08) !important; }
    .text-success-faded { color: rgba(40, 167, 69, 0.05) !important; }
    .text-info-faded { color: rgba(23, 162, 184, 0.05) !important; }
    .small-box .icon > i { font-size: 40px; position: absolute; top: 10px; right: 10px; z-index: 0; }
    .small-box .inner { padding: 5px; }
    .list-group-item { padding: 0.5rem 0; }
</style>
@stop