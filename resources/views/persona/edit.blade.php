@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h4><i class="fas fa-user-edit"></i> Editar Personal: {{ $persona->nombres }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('personas.update', $persona->id) }}" method="POST" id="formEditPersona">
                @csrf
                @method('PUT') {{-- Obligatorio para actualizaciones en Laravel --}}
                
                <h5 class="text-muted mb-3 border-bottom pb-2">Datos Personales</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="nombres" class="form-control" value="{{ $persona->nombres }}" required minlength="2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="{{ $persona->apellidos }}" required minlength="2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $persona->fecha_nacimiento }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Género</label>
                        <select name="genero" class="form-control">
                            <option value="Masculino" {{ $persona->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ $persona->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ $persona->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nacionalidad</label>
                        <input type="text" name="nacionalidad" class="form-control" value="{{ $persona->nacionalidad }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Teléfono / Celular</label>
                        <input type="text" name="telefono" id="telefono_edit" class="form-control" 
                               value="{{ $persona->telefono }}" maxlength="8" pattern="[0-9]{7,8}">
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Dirección de Domicilio</label>
                        <input type="text" name="direccion" class="form-control" value="{{ $persona->direccion }}">
                    </div>
                </div>

                <h5 class="text-muted mt-4 mb-3 border-bottom pb-2">Información Laboral</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Trabajador</label>
                        <input type="text" name="tipo_trabajador" class="form-control" value="{{ $persona->tipo_trabajador }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Modalidad de Ítem</label>
                        <select name="item" class="form-control" required>
                            <option value="Item TGN" {{ $persona->item == 'Item TGN' ? 'selected' : '' }}>Item TGN</option>
                            <option value="Item SUS" {{ $persona->item == 'Item SUS' ? 'selected' : '' }}>Item SUS</option>
                            <option value="Contrato" {{ $persona->item == 'Contrato' ? 'selected' : '' }}>Contrato</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Usuario vinculado</label>
                        <select name="usuario_id" class="form-control" required>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" {{ $persona->usuario_id == $u->id ? 'selected' : '' }}>
                                    {{ $u->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('personas.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-info">Actualizar Información</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script>
    // Filtro numérico para el teléfono en edición
    document.getElementById('telefono_edit').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.getElementById('formEditPersona').addEventListener('submit', function(e) {
        let tel = document.getElementById('telefono_edit').value;
        if(tel.length > 0 && tel.length < 7) {
            alert('El número de teléfono debe tener al menos 7 u 8 dígitos.');
            e.preventDefault();
        }
    });
</script>
@stop
@endsection