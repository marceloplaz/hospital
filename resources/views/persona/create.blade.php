@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="fas fa-user-plus"></i> Registrar Nuevo Personal</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('personas.store') }}" method="POST" id="formPersona">
                @csrf
                
                <h5 class="text-muted mb-3 border-bottom pb-2">Datos Personales</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="nombres" class="form-control" required minlength="2" placeholder="Ej. Juan" value="{{ old('nombres') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" required minlength="2" placeholder="Ej. Pérez" value="{{ old('apellidos') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" max="{{ date('Y-m-d') }}" value="{{ old('fecha_nacimiento') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Género</label>
                        <select name="genero" class="form-control">
                            <option value="">Seleccione...</option>
                            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nacionalidad</label>
                        <input type="text" name="nacionalidad" class="form-control" placeholder="Ej. Boliviana" value="{{ old('nacionalidad') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Teléfono / Celular</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" 
                               placeholder="Ej. 70012345" maxlength="8" pattern="[0-9]{7,8}" value="{{ old('telefono') }}">
                        <small class="text-muted">Mínimo 7-8 dígitos numéricos.</small>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Dirección de Domicilio</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Calle, Av. o Barrio" value="{{ old('direccion') }}">
                    </div>
                </div>

                <h5 class="text-muted mt-4 mb-3 border-bottom pb-2">Información Laboral</h5>
                <div class="row">
                    {{-- MODIFICACIÓN AQUÍ: DE INPUT A SELECT --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Trabajador (Cargo)</label>
                        <select name="tipo_trabajador" class="form-control @error('tipo_trabajador') is-invalid @enderror" required>
                            <option value="">-- Seleccione Cargo --</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->nombre }}" {{ old('tipo_trabajador') == $rol->nombre ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_trabajador')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Modalidad de Ítem</label>
                        <select name="item" class="form-control" required>
                            <option value="Item TGN" {{ old('item') == 'Item TGN' ? 'selected' : '' }}>Item TGN</option>
                            <option value="Item SUS" {{ old('item') == 'Item SUS' ? 'selected' : '' }}>Item SUS</option>
                            <option value="Contrato" {{ old('item', 'Contrato') == 'Contrato' ? 'selected' : '' }}>Contrato</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Vincular a Cuenta de Usuario</label>
                        <select name="usuario_id" class="form-control @error('usuario_id') is-invalid @enderror" required>
                            <option value="">Seleccione un usuario...</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('usuario_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>
                <div class="text-end">
                    <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Personal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    document.getElementById('telefono').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.getElementById('formPersona').addEventListener('submit', function(e) {
        let tel = document.getElementById('telefono').value;
        if(tel.length > 0 && tel.length < 7) {
            alert('El número de teléfono debe tener al menos 7 u 8 dígitos.');
            e.preventDefault();
        }
    });
</script>
@endpush
@endsection