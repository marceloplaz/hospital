@section('content')
<div class="container-fluid">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="callout callout-info shadow-sm">
                <h5><i class="fas fa-hand-holding-medical"></i> ¡Bienvenido, {{ $usuario->persona->nombres ?? $usuario->nombre }}!</h5>
                <p>Tu modalidad de financiamiento actual: <strong>{{ $usuario->persona->item ?? 'No asignada' }}</strong></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalTGN }}</h3>
                    <p>Personal TGN</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="{{ route('personas.index', ['item' => 'Item TGN']) }}" class="small-box-footer">
                    Ver listado <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalSUS }}</h3>
                    <p>Personal SUS</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <a href="{{ route('personas.index', ['item' => 'Item SUS']) }}" class="small-box-footer">
                    Ver listado <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalContrato }}</h3>
                    <p>Contratos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <a href="{{ route('personas.index', ['item' => 'Contrato']) }}" class="small-box-footer">
                    Ver listado <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gray-dark">
                <div class="inner">
                    <h3>{{ $totalPersonal }}</h3>
                    <p>Total Personal</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('personas.index') }}" class="small-box-footer">
                    Gestión total <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title text-bold">
                        <i class="fas fa-concierge-bell mr-1"></i> Mis Servicios Asignados
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Servicio</th>
                                <th>Descripción</th>
                                <th>Fecha Ingreso</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuario->servicios as $ser)
                                <tr>
                                    <td class="text-bold">{{ $ser->nombre }}</td>
                                    <td>{{ $ser->pivot->descripcion_usuario_servicio }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ser->pivot->fecha_ingreso)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $ser->pivot->estado == 'Activo' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $ser->pivot->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No tienes servicios asignados aún.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop