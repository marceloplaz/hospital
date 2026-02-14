<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // Cambiado a Usuario (tu modelo)
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
    // Inicializamos como colección vacía para que la vista no falle
    $todosLosTurnosSemana = collect(); 

    if ($servicio_id) {
        $servicio = Servicio::find($servicio_id);
        if ($servicio) {
            $usuarios = $servicio->usuarios()->where('usuario.estado', 1)->get();
            
            if ($semana_id && $usuarios->isNotEmpty()) {
                $usuarios->load(['turnosAsignados' => function($q) use ($semana_id, $servicio_id) {
                    $q->where('semana_id', $semana_id)
                      ->where('servicio_id', $servicio_id)
                      ->with('turnoDetalle'); 
                }]);

                // LLENAMOS la variable para el buscador del modal
                $todosLosTurnosSemana = TurnoAsignado::where('semana_id', $semana_id)
                    ->where('servicio_id', $servicio_id)
                    ->with(['usuario', 'turnoDetalle'])
                    ->get();
            }
        }
    }

    // IMPORTANTE: Se agregó 'todosLosTurnosSemana' al final del compact
    return view('turnos.create', compact(
        'usuarios', 'servicios', 'semanas', 'meses', 'turnosDisponibles',
        'servicio_id', 'semana_id', 'mes_id', 'anio', 'todosLosTurnosSemana'
    ));
}

 public function intercambiar(Request $request)
{
    $request->validate([
        'turno_origen_id' => 'required|exists:turno_asignado,id',
        'turno_destino_id' => 'required|exists:turno_asignado,id',
    ]);

    try {
        DB::beginTransaction();
        $tA = TurnoAsignado::findOrFail($request->turno_origen_id);
        $tB = TurnoAsignado::findOrFail($request->turno_destino_id);

        $userA = $tA->usuario_id;
        $userB = $tB->usuario_id;

        // Swapping de usuarios
        $tA->usuario_id = $userB;
        $tA->observacion = "Cambio: " . $tB->dia;
        $tA->save();

        $tB->usuario_id = $userA;
        $tB->observacion = "Cambio: " . $tA->dia;
        $tB->save();

        DB::commit();
        return back()->with('success', '¡Intercambio realizado entre diferentes días!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}
    public function store_rapido(Request $request)
    {
        $request->validate([
            'turno_asignado_id' => 'required|exists:turnos_asignados,id',
            'usuario_id' => 'nullable' 
        ]);

        $asig = TurnoAsignado::findOrFail($request->turno_asignado_id);
        
        if (empty($request->usuario_id)) {
            $asig->delete();
            return back()->with('success', 'Turno eliminado.');
        }

        $asig->usuario_id = $request->usuario_id;
        $asig->save();

        return back()->with('success', 'Médico actualizado.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id'  => 'required',
            'dia'         => 'required',
            'semana_id'   => 'required',
            'servicio_id' => 'required',
            'turno_id'    => 'required',
        ]);

        TurnoAsignado::create([
            'usuario_id'  => $request->usuario_id,
            'semana_id'   => $request->semana_id,
            'servicio_id' => $request->servicio_id,
            'dia'         => $request->dia,
            'turno_id'    => $request->turno_id,
            'estado'      => 1,
            'observacion' => null
        ]);

        return back()->with('success', 'Turno asignado correctamente.');
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
                        'estado'   => 1
                    ]
                );
            }
        }

        return $this->redireccionLimpia($request, '¡Semana replicada en todo el mes!');
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
            $request->validate(['mes_id' => 'required', 'servicio_id' => 'required']);
            $semanasIds = Semana::where('mes_id', $request->mes_id)->pluck('id');
            TurnoAsignado::whereIn('semana_id', $semanasIds)
                ->where('servicio_id', $request->servicio_id)
                ->delete();

            return $this->redireccionLimpia($request, '¡Se han eliminado todos los turnos del mes!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al vaciar el mes: ' . $e->getMessage());
        }
    }

    private function redireccionLimpia($request, $mensaje, $tipo = 'success')
    {
        return redirect()->route('turnos.create', [
            'servicio_id' => $request->servicio_id,
            'mes_id'      => $request->mes_id,
            'semana_id'   => $request->semana_id,
            'anio'        => $request->anio ?? 2026
        ])->with($tipo, $mensaje);
    }
}