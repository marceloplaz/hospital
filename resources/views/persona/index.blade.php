@extends('adminlte::page')

@section('title', 'Directorio de Personal Médico')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center px-2">
        <h1 class="text-health text-bold"><i class="fas fa-hospital-user mr-2"></i>Gestión de Personal Médico</h1>
        
        {{-- BOTÓN NUEVO: Si es ID 1, 2 o tiene el correo de admin --}}
        @if(auth()->user()->id == 1 || auth()->user()->id == 2 || auth()->user()->email == 'marcelo@gmail.com' || auth()->user()->roles->count() > 0)
            <a href="{{ route('personas.create') }}" class="btn btn-health shadow-sm">
                <i class="fas fa-plus-circle mr-1"></i> Nuevo Registro
            </a>
        @endif
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-health alert-dismissible fade show shadow-sm border-0">
            <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>
            <h5 class="mb-1"><i class="icon fas fa-check-circle"></i> ¡Completado!</h5>
            {{ session('success') }}
        </div>
    @endif

    <div class="card card-health-outline shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h3 class="card-title text-muted mb-0"><i class="fas fa-list-ul mr-2 text-health"></i>Personal y Especialistas</h3>
            
            <div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
                <label class="btn btn-outline-success btn-sm active">
                    <input type="radio" name="filterFin" value="" checked> Todos
                </label>
                <label class="btn btn-outline-success btn-sm">
                    <input type="radio" name="filterFin" value="TGN"> TGN
                </label>
                <label class="btn btn-outline-success btn-sm">
                    <input type="radio" name="filterFin" value="SUS"> SUS
                </label>
                <label class="btn btn-outline-success btn-sm">
                    <input type="radio" name="filterFin" value="CONTRATO"> Contrato
                </label>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tablaPersonal" class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Especialista</th>
                            <th>Identificación</th>
                            <th class="text-center">Sexo</th>
                            <th>Contacto</th>
                            <th>Cargo / Salario</th>
                            <th class="text-center">Acceso</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personas as $persona)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center pl-2">
                                    <div class="avatar-health mr-3">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div>
                                        <div class="text-bold text-dark">{{ $persona->nombres }} {{ $persona->apellidos }}</div>
                                        <div class="text-xs text-muted font-italic">{{ $persona->usuario->email ?? 'Sin correo' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <span class="badge badge-light border text-secondary px-2 py-1">{{ $persona->ci }}</span>
                                <small class="d-block text-muted text-xs mt-1"><i class="fas fa-globe-americas mr-1"></i>{{ $persona->nacionalidad ?? 'boliviana' }}</small>
                            </td>

                            <td class="align-middle text-center">
                                @if(strtolower($persona->genero) == 'masculino')
                                    <span class="gender-tag male"><i class="fas fa-mars mr-1"></i>M</span>
                                @elseif(strtolower($persona->genero) == 'femenino')
                                    <span class="gender-tag female"><i class="fas fa-venus mr-1"></i>F</span>
                                @else
                                    <span class="gender-tag other"><i class="fas fa-user mr-1"></i>O</span>
                                @endif
                            </td>

                            <td class="align-middle">
                                <div class="text-sm"><i class="fas fa-phone text-health-muted mr-1"></i> {{ $persona->telefono ?? 'S/N' }}</div>
                                <div class="text-xs text-muted text-truncate" style="max-width: 140px;" title="{{ $persona->direccion }}">
                                    <i class="fas fa-map-marker-alt text-danger opacity-50 mr-1"></i> {{ $persona->direccion ?? 'Sin dir.' }}
                                </div>
                            </td>

                            <td class="align-middle">
                                <span class="badge badge-health-soft text-uppercase mb-1" style="font-size: 0.65rem;">{{ $persona->tipo_trabajador }}</span>
                                <div class="text-sm font-weight-bold text-dark">
                                    {{ number_format($persona->salario, 0, '', '') }}
                                </div>
                                <small class="text-muted"><i class="fas fa-file-invoice-dollar mr-1"></i>{{ $persona->tipo_salario ?? 'S/T' }}</small>
                            </td>

                            <td class="align-middle text-center">
                                @if($persona->usuario_id)
                                    <span class="dot-status {{ $persona->usuario->estado == 1 ? 'bg-success' : 'bg-danger' }}"></span>
                                    <span class="text-xs font-weight-bold {{ $persona->usuario->estado == 1 ? 'text-success' : 'text-danger' }}">
                                        {{ $persona->usuario->estado == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                    </span>
                                @else
                                    <span class="badge badge-pill bg-gray-light text-muted border text-xs">PENDIENTE</span>
                                @endif
                            </td>

                            <td class="text-center align-middle">
                                {{-- ACCIONES: Solo para Admin (ID 1, 2 o correo específico) --}}
                                @if(auth()->user()->id == 1 || auth()->user()->id == 2 || auth()->user()->email == 'marcelo@gmail.com')
                                    <div class="btn-group border rounded bg-white shadow-xs">
                                        @if(!$persona->usuario_id)
                                            <button class="btn btn-sm btn-white text-health" data-toggle="modal" data-target="#modalUsuario{{ $persona->id }}" title="Habilitar Cuenta">
                                                <i class="fas fa-shield-alt"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('personas.toggle', $persona->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-white {{ $persona->usuario->estado == 1 ? 'text-danger' : 'text-success' }}" title="Cambiar Estado">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="#" class="btn btn-sm btn-white text-info border-left" title="Editar Expediente">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted text-xs"><i class="fas fa-lock mr-1"></i> Solo lectura</span>
                                @endif
                            </td>
                        </tr>

                        {{-- MODAL USUARIO --}}
                        <div class="modal fade" id="modalUsuario{{ $persona->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('personas.asignar') }}" method="POST" class="w-100">
                                    @csrf
                                    <input type="hidden" name="persona_id" value="{{ $persona->id }}">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header bg-health text-white">
                                            <h5 class="modal-title font-weight-light"><i class="fas fa-lock mr-2"></i>Crear Acceso al Sistema</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body bg-light-gray p-4">
                                            <div class="text-center mb-4">
                                                <div class="avatar-modal mb-2 mx-auto"><i class="fas fa-user-shield"></i></div>
                                                <h6 class="font-weight-bold">{{ $persona->nombres }} {{ $persona->apellidos }}</h6>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-xs text-uppercase text-muted">Correo Institucional</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope text-health"></i></span></div>
                                                    <input type="email" name="email" class="form-control border-left-0" placeholder="correo@hospital.com" required>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="text-xs text-uppercase text-muted">Contraseña (Inicial: C.I.)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-key text-health"></i></span></div>
                                                    <input type="text" name="password" class="form-control border-left-0" value="{{ $persona->ci }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-3">
                                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-health px-4 shadow">Confirmar Acceso</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        :root { --health-color: #28a745; --health-hover: #218838; --health-bg: #eafaf1; --health-muted: #82c91e; }
        .text-health { color: var(--health-color) !important; }
        .bg-health { background-color: var(--health-color) !important; }
        .text-health-muted { color: var(--health-muted) !important; }
        .btn-health { background-color: var(--health-color); color: white; border-radius: 6px; font-weight: 600; }
        .btn-health:hover { background-color: var(--health-hover); color: white; transform: translateY(-1px); transition: 0.2s; }
        .alert-health { background-color: var(--health-color); color: white; border-radius: 8px; }
        .card-health-outline { border-top: 5px solid var(--health-color); border-radius: 12px; }
        .table thead th { background-color: #f8fbf9; color: #495057; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid #e0f2e9; }
        .table tbody tr:hover { background-color: #f1fcf5; transition: 0.3s; }
        .avatar-health { width: 42px; height: 42px; background-color: var(--health-bg); color: var(--health-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .gender-tag { padding: 3px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: bold; }
        .male { background-color: #e3f2fd; color: #0d6efd; }
        .female { background-color: #fff0f6; color: #d63384; }
        .other { background-color: #f8f9fa; color: #6c757d; }
        .badge-health-soft { background-color: #d1f2eb; color: #117a65; border: 1px solid #a3e4d7; }
        .dot-status { height: 8px; width: 8px; border-radius: 50%; display: inline-block; margin-right: 4px; }
        .avatar-modal { width: 60px; height: 60px; background-color: var(--health-bg); color: var(--health-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
        .bg-light-gray { background-color: #fdfdfd; }
        .btn-outline-success { color: var(--health-color); border-color: var(--health-color); }
        .btn-outline-success:hover, .btn-outline-success.active { background-color: var(--health-color) !important; color: white !important; }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#tablaPersonal')) {
                $('#tablaPersonal').DataTable().destroy();
            }

            var table = $('#tablaPersonal').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
                "responsive": true,
                "autoWidth": false,
                "order": [[0, "asc"]],
                "pageLength": 10,
                // Aseguramos que la columna 6 (Acciones) no sea ordenable
                "columnDefs": [
                    { "targets": 6, "orderable": false, "searchable": false }
                ]
            });

            $('input[name="filterFin"]').on('change', function() {
                table.column(4).search($(this).val()).draw();
            });
        });
    </script>
@stop