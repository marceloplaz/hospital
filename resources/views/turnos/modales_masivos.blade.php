<div class="modal fade" id="modalClonar" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('turnos.clonar') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="servicio_id" value="{{ $servicio_id }}">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Clonar Programación</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <label>Mes de Origen:</label>
                <select name="mes_origen_id" class="form-control mb-3">
                    @foreach($meses as $m) <option value="{{ $m->id }}" {{ $mes_id == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option> @endforeach
                </select>
                <label>Mes de Destino:</label>
                <select name="mes_destino_id" class="form-control">
                    @foreach($meses as $m) <option value="{{ $m->id }}">{{ $m->nombre }}</option> @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Iniciar Clonación</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalRotar" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('turnos.rotar') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="servicio_id" value="{{ $servicio_id }}">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Rotar Médicos</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p>Esta acción moverá los turnos del <b>Mes Origen</b> al <b>Mes Destino</b> rotando los médicos un lugar en la lista.</p>
                <label>Mes de Origen:</label>
                <select name="mes_origen_id" class="form-control mb-3">
                    @foreach($meses as $m) <option value="{{ $m->id }}" {{ $mes_id == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option> @endforeach
                </select>
                <label>Mes de Destino:</label>
                <select name="mes_destino_id" class="form-control">
                    @foreach($meses as $m) <option value="{{ $m->id }}">{{ $m->nombre }}</option> @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Ejecutar Rotación</button>
            </div>
        </form>
    </div>
</div>