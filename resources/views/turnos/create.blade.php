@extends('adminlte::page')

@section('title', 'Asignación de Turnos')

@section('content')
<div class="container-fluid pt-4">
    {{-- Alertas de Feedback --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3 alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3 alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card card-outline card-success shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="card-title text-success font-weight-bold text-uppercase">
                <i class="fas fa-calendar-check mr-2"></i> Gestión de Turnos Médicos
            </h3>

            @if($servicio_id && $semana_id)
            <div class="card-tools ml-auto">
                <div class="dropdown d-inline">
                    <button class="btn btn-dark dropdown-toggle btn-sm shadow-sm" type="button" data-toggle="dropdown">
                        <i class="fas fa-layer-group mr-1"></i> ACCIONES MASIVAS
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <form action="{{ route('turnos.clonarSemanaMes') }}" method="POST" onsubmit="return confirm('¿Replicar esta semana en todo el mes?')">
                            @csrf
                            <input type="hidden" name="semana_id" value="{{ $semana_id }}">
                            <input type="hidden" name="servicio_id" value="{{ $servicio_id }}">
                            <input type="hidden" name="mes_id" value="{{ $mes_id }}">
                            <input type="hidden" name="anio" value="{{ $anio }}">
                            <button type="submit" class="dropdown-item text-warning">
                                <i class="fas fa-magic mr-2"></i> Replicar semana en el mes
                            </button>
                        </form>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('turnos.eliminarMes') }}" method="POST" onsubmit="return confirm('¿Vaciar todo el mes?')">
                            @csrf
                            <input type="hidden" name="mes_id" value="{{ $mes_id }}">
                            <input type="hidden" name="servicio_id" value="{{ $servicio_id }}">
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt mr-2"></i> Vaciar Mes</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="card-body">
            {{-- FILTROS --}}
            <div class="row bg-light p-3 rounded mb-4 border shadow-sm">
                <div class="col-md-3">
                    <label class="small font-weight-bold">SERVICIO</label>
                    <select id="servicio_id" class="form-control" onchange="actualizarFiltros()">
                        <option value="">-- Seleccionar --</option>
                        @foreach($servicios as $ser)
                            <option value="{{ $ser->id }}" {{ $servicio_id == $ser->id ? 'selected' : '' }}>{{ $ser->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold">AÑO</label>
                    <select id="anio_selector" class="form-control" onchange="actualizarFiltros()">
                        <option value="2025" {{ $anio == 2025 ? 'selected' : '' }}>2025</option>
                        <option value="2026" {{ $anio == 2026 ? 'selected' : '' }}>2026</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">MES</label>
                    <select id="mes_id" class="form-control" onchange="actualizarFiltros()">
                        <option value="">-- Seleccionar --</option>
                        @foreach($meses as $m)
                            <option value="{{ $m->id }}" {{ $mes_id == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold">SEMANA</label>
                    <select id="semana_id" class="form-control" onchange="actualizarFiltros()" {{ !$mes_id ? 'disabled' : '' }}>
                        <option value="">-- Seleccionar Semana --</option>
                        @foreach($semanas as $sem)
                            <option value="{{ $sem->id }}" {{ $semana_id == $sem->id ? 'selected' : '' }}>
                                Semana {{ $sem->numero }} ({{ $sem->fecha_inicio }} - {{ $sem->fecha_fin }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-success text-white">
                        <tr>
                            <th class="text-left" style="width: 220px;">MÉDICO</th>
                            @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                <th>{{ $dia }}</th>
                            @endforeach
                            <th class="bg-dark" style="width: 80px;">HRS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($servicio_id && $semana_id && count($usuarios) > 0)
                            @foreach($usuarios as $u)
                            <tr>
                                <td class="text-left font-weight-bold align-middle bg-light">
                                    <button class="btn btn-xs text-danger mr-1" onclick="vaciarFila('{{ $u->id }}')" title="Vaciar semana">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                    {{ $u->nombre }}
                                </td>
                                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                    <td class="p-2 align-middle cell-container" style="min-width: 140px;">
                                        @php $asignaciones = $u->turnosAsignados->where('dia', $dia); @endphp
                                        @forelse($asignaciones as $asig)
                                            <div class="turno-box mb-1 border-primary">
                                                <form action="{{ route('turnos.store_rapido') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="turno_asignado_id" value="{{ $asig->id }}">
                                                    
                                                    <div class="badge badge-primary d-block mb-1 p-2 shadow-sm">
                                                        <div class="text-uppercase font-weight-bold" style="font-size: 0.65rem;">
                                                            {{ $asig->turnoDetalle->nombre_turno ?? 'S/N' }}
                                                        </div>
                                                        @if(isset($asig->turnoDetalle->hora_inicio))
                                                            <div style="font-size: 0.6rem; border-top: 1px solid rgba(255,255,255,0.3); margin-top: 2px; padding-top: 1px;">
                                                                <i class="far fa-clock"></i> 
                                                                {{ date('H:i', strtotime($asig->turnoDetalle->hora_inicio)) }} - 
                                                                {{ date('H:i', strtotime($asig->turnoDetalle->hora_fin)) }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <select name="usuario_id" class="form-control form-control-sm select-auto" onchange="this.form.submit()">
                                                        @foreach($usuarios as $med)
                                                            <option value="{{ $med->id }}" {{ $asig->usuario_id == $med->id ? 'selected' : '' }}>{{ $med->nombre }}</option>
                                                        @endforeach
                                                        <option value="">-- Quitar --</option>
                                                    </select>

                                                    {{-- BOTÓN DE INTERCAMBIO ACTUALIZADO --}}
                                                    <button type="button" class="btn btn-xs btn-secondary btn-block mt-1" 
                                                            style="font-size: 8px; font-weight: bold;"
                                                            onclick="abrirModalIntercambio({
                                                                id: '{{ $asig->id }}',
                                                                medico: '{{ $u->nombre }}',
                                                                dia: '{{ $dia }}',
                                                                turno: '{{ $asig->turnoDetalle->nombre_turno }}',
                                                                horario: '{{ date('H:i', strtotime($asig->turnoDetalle->hora_inicio)) }} - {{ date('H:i', strtotime($asig->turnoDetalle->hora_fin)) }}'
                                                            })">
                                                        <i class="fas fa-sync-alt"></i> INTERCAMBIAR
                                                    </button>

                                                    @if($asig->observacion)
                                                        <div class="mt-2">
                                                            <span style="color: #ffffff; font-size: 9px; font-weight: bold; background-color: #dc3545; padding: 3px 5px; border-radius: 3px; display: block; text-transform: uppercase;">
                                                                <i class="fas fa-exchange-alt"></i> {{ $asig->observacion }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        @empty
                                            <button type="button" class="btn btn-xs btn-outline-success btn-add-cell" 
                                                    onclick="abrirModalAsignar('{{ $u->id }}', '{{ $dia }}')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        @endforelse
                                    </td>
                                @endforeach
                                <td class="align-middle font-weight-bold text-primary bg-light">
                                    {{ $u->turnosAsignados->sum(fn($a) => $a->turnoDetalle->duracion_horas ?? 0) }}h
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr><td colspan="9" class="py-5 text-muted">Seleccione los filtros para visualizar la cuadrícula</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ASIGNAR NUEVO --}}
<div class="modal fade" id="modalAsignarRapido" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-success shadow-lg">
            <form action="{{ route('turnos.store') }}" method="POST">
                @csrf
                <input type="hidden" name="usuario_id" id="modal_u_id">
                <input type="hidden" name="dia" id="modal_dia">
                <input type="hidden" name="semana_id" id="modal_semana_id">
                <input type="hidden" name="servicio_id" id="modal_servicio_id">
                <input type="hidden" name="mes_id" value="{{ $mes_id }}">
                <input type="hidden" name="anio" value="{{ $anio }}">
                <div class="modal-header bg-success text-white py-2"><h6 class="modal-title font-weight-bold">ASIGNAR TURNO</h6></div>
                <div class="modal-body">
                    <select name="turno_id" class="form-control" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($turnosDisponibles as $t)
                            <option value="{{ $t->id_turno }}">{{ $t->nombre_turno }} ({{ $t->duracion_horas }}h)</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer p-2"><button type="submit" class="btn btn-success btn-sm btn-block">GUARDAR</button></div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL INTERCAMBIAR CON BUSCADOR --}}
<div class="modal fade" id="modalIntercambio" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-danger shadow-lg">
            <form action="{{ route('turnos.intercambiar') }}" method="POST">
                @csrf
                <input type="hidden" name="turno_origen_id" id="inter_asig_id">
                
                <div class="modal-header bg-danger text-white py-2">
                    <h6 class="modal-title font-weight-bold">INTERCAMBIO LIBRE (DÍAS/MÉDICOS)</h6>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <small><b>Turno seleccionado:</b> <span id="info_origen"></span></small>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">BUSCAR TURNO DESTINO:</label>
                        <select name="turno_destino_id" class="form-control select2" style="width: 100%" required>
                            <option value="">-- Seleccione Médico y Día --</option>
                            @foreach($todosLosTurnosSemana as $ts)
                                <option value="{{ $ts->id }}">
                                    {{-- Usamos el nombre del usuario y el día --}}
                                    {{ $ts->usuario->nombre ?? 'Sin nombre' }} - {{ $ts->dia }} ({{ $ts->turnoDetalle->nombre_turno ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">El médico seleccionado intercambiará su lugar con el original.</small>
                    </div>
                </div>
                
                <div class="modal-footer p-2">
                    <button type="submit" class="btn btn-danger btn-block">CONFIRMAR CAMBIO</button>
                </div>
            </form>
        </div>
    </div>
</div>
<form id="formVaciarFila" action="{{ route('turnos.destroy', 0) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
    <input type="hidden" name="usuario_id" id="vaciar_u_id">
    <input type="hidden" name="semana_id" value="{{ $semana_id }}">
    <input type="hidden" name="servicio_id" value="{{ $servicio_id }}">
</form>
@stop

@section('css')
<style>
    .select-auto { font-size: 0.75rem; height: 28px; padding: 2px 4px; border-radius: 4px; }
    .cell-container:hover { background-color: #f8fff8; }
    .btn-add-cell { border-radius: 50%; width: 28px; height: 28px; padding: 0; opacity: 0.1; border: 1px dashed #28a745; }
    .cell-container:hover .btn-add-cell { opacity: 1; transform: scale(1.1); }
    .turno-box { border: 1px solid #cbd5e0; border-radius: 6px; padding: 4px; background: #fff; margin-bottom: 5px; }
    .badge-primary { background-color: #007bff; }
</style>
@stop

@section('js')
<script>
    function actualizarFiltros() {
        const url = new URL(window.location.href);
        const params = {
            servicio_id: document.getElementById('servicio_id').value,
            anio: document.getElementById('anio_selector').value,
            mes_id: document.getElementById('mes_id').value,
            semana_id: document.getElementById('semana_id').value
        };
        Object.keys(params).forEach(k => params[k] ? url.searchParams.set(k, params[k]) : url.searchParams.delete(k));
        window.location.href = url.pathname + '?' + url.searchParams.toString();
    }

    function abrirModalAsignar(uId, dia) {
        document.getElementById('modal_u_id').value = uId;
        document.getElementById('modal_dia').value = dia;
        document.getElementById('modal_semana_id').value = document.getElementById('semana_id').value;
        document.getElementById('modal_servicio_id').value = document.getElementById('servicio_id').value;
        $('#modalAsignarRapido').modal('show');
    }

    function vaciarFila(uId) {
        if(confirm('¿Vaciar los turnos de la semana?')) {
            document.getElementById('vaciar_u_id').value = uId;
            document.getElementById('formVaciarFila').submit();
        }
    }

    // FUNCIÓN CORREGIDA PARA EL MODAL
    function abrirModalIntercambio(data) {
    document.getElementById('inter_asig_id').value = data.id;
    document.getElementById('info_origen').innerText = data.medico + " (" + data.dia + " - " + data.turno + ")";
    
    // Si usas Select2 para el buscador:
    $('.select2').val(null).trigger('change'); 
    
    $('#modalIntercambio').modal('show');
}
</script>
@stop