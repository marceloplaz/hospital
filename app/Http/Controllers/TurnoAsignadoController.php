<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Servicio;
use App\Models\Semana;
use App\Models\TurnoAsignado;
use App\Models\Turno;
use App\Models\Mes; 
use Illuminate\Support\Facades\DB;

class TurnoAsignadoController extends Controller
{
    public function create(Request $request)
    {
        $servicios = Servicio::all();
        $turnosDisponibles = Turno::all();
        $meses = Mes::orderBy('id', 'asc')->get(); 
        
        $anio = $request->get('anio', 2026);
        $mes_id = $request->get('mes_id');
        $semana_id = $request->get('semana_id');
        $servicio_id = $request->get('servicio_id');

        $semanas = $mes_id ? Semana::where('mes_id', $mes_id)->get() : collect();
        
        $usuarios = collect();
        if ($servicio_id) {
            $servicio = Servicio::find($servicio_id);
            if ($servicio) {
                // Obtenemos todos los usuarios del servicio
                $usuarios = $servicio->usuarios()->where('usuario.estado', 1)->get();
                
                if ($semana_id && $usuarios->isNotEmpty()) {
                    $usuarios->load(['turnosAsignados' => function($q) use ($semana_id, $servicio_id) {
                        $q->where('semana_id', $semana_id)
                          ->where('servicio_id', $servicio_id)
                          ->with('turnoDetalle');
                    }]);
                }
            }
        }

        return view('turnos.create', compact(
            'usuarios', 'servicios', 'semanas', 'meses', 'turnosDisponibles',
            'servicio_id', 'semana_id', 'mes_id', 'anio'
        ));
    }

    // Esta es la función que llama el SELECT de la tabla
    public function store_rapido(Request $request)
    {
        $request->validate([
            'turno_asignado_id' => 'required',
            'usuario_id' => 'nullable'
        ]);

        $asignacion = TurnoAsignado::find($request->turno_asignado_id);
        
        if (!$asignacion) {
            return $this->redireccionLimpia($request, 'No se encontró la asignación', 'error');
        }

        if (empty($request->usuario_id)) {
            $asignacion->delete();
            return $this->redireccionLimpia($request, 'Turno quitado correctamente');
        }

        $asignacion->update(['usuario_id' => $request->usuario_id]);
        return $this->redireccionLimpia($request, 'Médico reasignado con éxito');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'usuario_id'  => 'required',
                'semana_id'   => 'required',
                'servicio_id' => 'required',
                'turno_id'    => 'required', 
                'dia'         => 'required'
            ]);

            TurnoAsignado::create([
                'usuario_id'  => $request->usuario_id,
                'semana_id'   => $request->semana_id,
                'servicio_id' => $request->servicio_id,
                'turno_id'    => $request->turno_id,
                'dia'         => $request->dia,
                'estado'      => 'Asignado'
            ]);

            return $this->redireccionLimpia($request, 'Turno asignado correctamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function clonarSemanaMes(Request $request)
    {
        $turnosBase = TurnoAsignado::where('semana_id', $request->semana_id)
                                    ->where('servicio_id', $request->servicio_id)
                                    ->get();

        if ($turnosBase->isEmpty()) {
            return back()->with('error', 'No hay turnos para replicar.');
        }

        $otrasSemanas = Semana::where('mes_id', $request->mes_id)
                              ->where('id', '!=', $request->semana_id)
                              ->get();

        foreach ($otrasSemanas as $semanaDestino) {
            foreach ($turnosBase as $turno) {
                TurnoAsignado::updateOrCreate(
                    [
                        'usuario_id'  => $turno->usuario_id,
                        'semana_id'   => $semanaDestino->id,
                        'servicio_id' => $turno->servicio_id,
                        'dia'         => $turno->dia,
                    ],
                    [
                        'turno_id' => $turno->turno_id, 
                        'estado'   => 'Asignado'
                    ]
                );
            }
        }

        return $this->redireccionLimpia($request, '¡Semana replicada en todo el mes!');
    }

    // Función auxiliar para evitar que se pierdan los médicos en la vista
    private function redireccionLimpia($request, $mensaje, $tipo = 'success')
    {
        return redirect()->route('turnos.create', [
            'servicio_id' => $request->servicio_id,
            'mes_id'      => $request->mes_id,
            'semana_id'   => $request->semana_id,
            'anio'        => $request->anio ?? 2026
        ])->with($tipo, $mensaje);
    }

    public function destroy(Request $request, $id)
    {
        if ($id == 0 && $request->has('usuario_id')) {
            TurnoAsignado::where('usuario_id', $request->usuario_id)
                ->where('semana_id', $request->semana_id)
                ->where('servicio_id', $request->servicio_id)
                ->delete();
                
            return $this->redireccionLimpia($request, 'Fila vaciada.');
        }

        $turno = TurnoAsignado::find($id);
        if ($turno) {
            $turno->delete();
            return $this->redireccionLimpia($request, 'Turno eliminado.');
        }

        return back()->with('error', 'No se pudo eliminar.');
    }
    public function eliminarMes(Request $request)
{
    try {
        // Validamos que tengamos los datos necesarios
        $request->validate([
            'mes_id' => 'required',
            'servicio_id' => 'required'
        ]);

        // Buscamos todas las semanas que pertenecen a ese mes
        $semanasIds = Semana::where('mes_id', $request->mes_id)->pluck('id');

        // Borramos todos los turnos de ese servicio en esas semanas
        TurnoAsignado::whereIn('semana_id', $semanasIds)
            ->where('servicio_id', $request->servicio_id)
            ->delete();

        return redirect()->route('turnos.create', [
            'servicio_id' => $request->servicio_id,
            'mes_id'      => $request->mes_id,
            'anio'        => $request->anio ?? 2026
        ])->with('success', '¡Se han eliminado todos los turnos del mes para este servicio!');

    } catch (\Exception $e) {
        return back()->with('error', 'Error al vaciar el mes: ' . $e->getMessage());
    }
}
}