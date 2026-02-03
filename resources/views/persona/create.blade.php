@extends('adminlte::page')

@section('title', 'Gestión de Turnos')

@section('content')
<div class="container-fluid pt-4">
    {{-- Alertas de Feedback --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Errores de Validación de Laravel --}}
    @if ($errors->any())
        <div class="alert alert-warning border-0 shadow-sm mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-outline card-success shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="card-title text-success font-weight-bold text-uppercase">
                <i class="fas fa-calendar-check mr-2"></i> Panel de Asignación de Turnos
            </h3>
        </div>

        <div class="card-body">
            {{-- FILTROS DE CABECERA --}}
            <div class="row bg-light p-3 rounded mb-4 border shadow-sm">
                <div class="col-md-3">
                    <label class="small font-weight-bold">SERVICIO</label>
                    <select id="servicio_id" class="form-control shadow-sm" onchange="actualizarFiltros()">
                        <option value="">-- Seleccionar --</option>
                        @foreach($servicios as $ser)
                            <option value="{{ $ser->id }}" {{ $servicio_id == $ser->id ? 'selected' : '' }}>{{ $ser->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold">AÑO</label>
                    <select id="anio_selector" class="form-control shadow-sm" onchange="actualizarFiltros()">
                        <option value="2025" {{ $anio == 2025 ? 'selected' : '' }}>2025</option>
                        <option value="2026" {{ $anio == 2026 ? 'selected' : '' }}>2026</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">MES</label>
                    <select id="mes_id" class="form-control shadow-sm" onchange="actualizarFiltros()">
                        <option value="">-- Seleccionar --</option>
                        @foreach($meses as $m)
                            <option value="{{ $m->id }}" {{ $mes_id == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold">SEMANA</label>
                    <select id="semana_id" class="form-control shadow-sm" onchange="actualizarFiltros()" {{ !$mes_id ? 'disabled' : '' }}>
                        <option value="">-- Seleccionar Semana --</option>
                        @foreach($semanas as $sem)
                            <option value="{{ $sem->id }}" {{ $semana_id == $sem->id ? 'selected' : '' }}>
                                Semana {{ $sem->numero }} ({{ $sem->fecha_inicio }} al {{ $sem->fecha_fin }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- TABLA DE TURNOS --}}
            <div class="table-responsive">
                <table class="table table-bordered text-center shadow-sm mb-0">
                    <thead class="bg-success text-white">
                        <tr>
                            <th style="width: 200px;" class="text-left pl-3">MÉDICO</th>
                            @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                <th>{{ $dia }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if($servicio_id && $semana_id && count($usuarios) > 0)
                            @foreach($usuarios as $u)
                            <tr>
                                <td class="text-left font-weight-bold pl-2 align-middle bg-light" style="font-size: 0.85rem;">
                                    <button class="btn btn-xs btn-link text-danger p-0 mr-1" onclick="vaciarFila('{{ $u->id }}')" title="Vaciar fila">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                    {{ $u->nombre }}
                                </td>
                                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                    <td class="p-1 align-middle cell-container" style="min-width: 130px;">
                                        @php $asignaciones = $u->turnosAsignados->where('dia', $dia); @endphp
                                        
                                        @forelse($asignaciones as $asig)
                                            {{-- MOSTRAR TURNO EXISTENTE --}}
                                            <div class="turno-item">
                                                <form action="{{ route('turnos.actualizarTurnoCuadricula') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="turno_asignado_id" value="{{ $asig->id }}">
                                                    
                                                    <span class="badge badge-primary d-block p-1 mb-1 text-uppercase shadow-xs" style="font-size: 0.6rem;">
                                                        {{ $asig->turnoDetalle->nombre_turno ?? 'S/N' }}
                                                    </span>

                                                    <select name="usuario_id" class="form-control form-control-sm select-mini" onchange="this.form.submit()">
                                                        @foreach($usuarios as $medico)
                                                            <option value="{{ $medico->id }}" {{ $u->id == $medico->id ? 'selected' : '' }}>
                                                                {{ $medico->nombre }}
                                                            </option>
                                                        @endforeach
                                                        <option value="">-- Quitar --</option>
                                                    </select>
                                                </form>
                                            </div>
                                        @empty
                                            {{-- SELECTOR DIRECTO PARA ASIGNAR NUEVO --}}
                                            <form action="{{ route('turnos.store') }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="usuario_id" value="{{ $u->id }}">
                                                <input type="hidden" name="dia" value="{{ $dia }}">
                                                <input type="hidden" name="semana_id" value="{{ $semana_id }}">
                                                <input type="hidden" name="servicio_id" value="{{ $servicio_id }}">

                                                <select name="turno_id" class="form-control form-control-sm select-add" onchange="this.form.submit()">
                                                    <option value="">+</option>
                                                    @foreach($turnosDisponibles as $t)
                                                        <option value="{{ $t->id_turno }}">{{ $t->nombre_turno }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endforelse
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="py-5 text-muted">
                                    <i class="fas fa-info-circle mr-2"></i> Seleccione los filtros para cargar la grilla.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Formulario oculto para vaciar fila --}}
<form id="formVaciarFila" action="{{ route('turnos.destroy', 0) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
    <input type="hidden" name="usuario_id" id="vaciar_u_id">
    <input type="hidden" name="semana_id" value="{{ $semana_id }}">
    <input type="hidden" name="servicio_id" value="{{ $servicio_id }}">
</form>

@stop

@section('css')
<style>
    .select-mini { font-size: 0.65rem; height: 22px; padding: 0 2px; }
    .select-add { 
        font-size: 0.8rem; 
        height: 28px; 
        border: 1px dashed #28a745; 
        color: #28a745; 
        text-align: center;
        background-color: transparent;
    }
    .select-add:hover { background-color: #f1fff2; cursor: pointer; }
    .badge-primary { background-color: #007bff !important; }
    .table td { padding: 4px !important; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
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

        const triggerId = event ? event.target.id : null;

        Object.keys(params).forEach(key => {
            if (params[key]) {
                if (key === 'semana_id' && (triggerId === 'mes_id' || triggerId === 'servicio_id')) {
                    url.searchParams.delete(key);
                } else {
                    url.searchParams.set(key, params[key]);
                }
            } else {
                url.searchParams.delete(key);
            }
        });
        window.location.href = url.pathname + '?' + url.searchParams.toString();
    }

    function vaciarFila(uId) {
        if(confirm('¿Desea eliminar todos los turnos de este médico para la semana actual?')) {
            document.getElementById('vaciar_u_id').value = uId;
            document.getElementById('formVaciarFila').submit();
        }
    }
</script>
@stop