@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="mb-3">
                <a href="{{ route('personas.index') }}" class="btn btn-link text-decoration-none p-0">
                    <i class="fas fa-arrow-left"></i> Volver al listado
                </a>
            </div>

            <div class="card shadow border-0 overflow-hidden">
                {{-- Encabezado dinámico --}}
                @php
                    $headerGradient = match($persona->tipo_trabajador) {
                        'SuperAdmin' => 'bg-danger',
                        'Médico'     => 'bg-primary',
                        'Enfermera', 'Enfermero' => 'bg-info',
                        default      => 'bg-secondary'
                    };
                @endphp
                
                <div class="card-header {{ $headerGradient }} py-4 text-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle p-2 me-3 shadow-sm">
                            <i class="fas fa-user-md fa-3x text-dark"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $persona->nombres }} {{ $persona->apellidos }}</h3>
                            {{-- Usamos el dato directo de la tabla personas --}}
                            <span class="badge bg-white text-dark rounded-pill">{{ $persona->tipo_trabajador }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="row g-0">
                        {{-- Información Personal --}}
                        <div class="col-md-6 border-end p-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-id-card me-2"></i>Información Personal
                            </h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted">Nacionalidad:</span>
                                    <span class="fw-bold">{{ $persona->nacionalidad ?? 'No registrada' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted">Género:</span>
                                    <span class="fw-bold">{{ $persona->genero }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted">Teléfono:</span>
                                    <span class="fw-bold text-primary">{{ $persona->telefono }}</span>
                                </li>
                                <li class="list-group-item border-0">
                                    <span class="text-muted d-block mb-1">Dirección:</span>
                                    <p class="mb-0 fw-bold">{{ $persona->direccion ?? 'Sin dirección' }}</p>
                                </li>
                            </ul>
                        </div>

                        {{-- Información Laboral --}}
                        <div class="col-md-6 p-4 bg-light">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-briefcase me-2"></i>Información Laboral
                            </h5>
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="small text-muted mb-1">Modalidad de Contrato</div>
                                    <h6 class="fw-bold mb-3">{{ $persona->item }}</h6>
                                    
                                    <div class="small text-muted mb-1">Cuenta de Usuario</div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle fa-2x text-secondary me-2"></i>
                                        <div>
                                            {{-- Acceso seguro al nombre de usuario --}}
                                            <div class="fw-bold">{{ $persona->usuario->nombre ?? 'Usuario no vinculado' }}</div>
                                            <div class="small text-muted">{{ $persona->usuario->email ?? 'S/E' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Editar Perfil
                                </a>
                                <a href="{{ route('personas.pdf', $persona->id) }}" target="_blank" class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-1"></i> Descargar Ficha PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection