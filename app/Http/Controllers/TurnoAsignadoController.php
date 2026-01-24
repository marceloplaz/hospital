<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Servicio;
use App\Models\Semana;
use App\Models\TurnoAsignado;
use App\Models\Turno;

class TurnoAsignadoController extends Controller
{
    // 1. Mostrar el panel de control (Matriz semanal)
    public function index(Request $request)
    {
        $servicios = Servicio::all();
        $semanas = Semana::with('mes')->get();

        $servicioId = $request->get('servicio_id');
        $semanaId = $request->get('semana_id');

        $servicioSeleccionado = $servicioId ? Servicio::find($servicioId) : null;
        $semanaSeleccionada = $semanaId ? Semana::with('mes')->find($semanaId) : null;

        $turnos = Usuario::query()
            ->when($servicioId, function($query) use ($servicioId) {
                return $query->where('servicio_id', $servicioId);
            })
            ->with(['turnosAsignados' => function($query) use ($servicioId, $semanaId) {
                if ($servicioId) {
                    $query->where('servicio_id', $servicioId);
                }
                if ($semanaId) {
                    $query->where('semana_id', $semanaId);
                }
                $query->with('turnoDetalle');
            }])
            ->get();

        return view('turnos.index', compact('turnos', 'servicios', 'semanas', 'servicioSeleccionado', 'semanaSeleccionada'));
    }

    // 2. Mostrar formulario para crear (MODIFICADO)
public function create(Request $request)
{
    $servicios = Servicio::all();
    $semanas = Semana::with('mes')->get();
    $turnosDisponibles = Turno::all();

    $selected_servicio = $request->get('servicio_id');
    $selected_usuario = $request->get('usuario_id');
    $selected_semana = $request->get('semana_id');

    // --- CORRECCIÓN CRÍTICA AQUÍ ---
    // Usamos 'with' para que cada usuario ya traiga sus turnos y los detalles del turno (horas)
    // Esto es lo que permite que la tabla en Blade muestre los datos
    $query = Usuario::where('estado', 1)
        ->with(['turnosAsignados' => function($q) use ($selected_semana, $selected_servicio) {
            if ($selected_semana) $q->where('semana_id', $selected_semana);
            if ($selected_servicio) $q->where('servicio_id', $selected_servicio);
            $q->with('turnoDetalle'); // Importante para sumar las horas
        }]);

    if ($selected_servicio) {
        // Si tienes la relación en el modelo Servicio, la usamos
        $servicioEncontrado = Servicio::find($selected_servicio);
        if ($servicioEncontrado) {
            $usuarios = $servicioEncontrado->usuarios()
                ->where('usuario.estado', 1)
                ->with(['turnosAsignados' => function($q) use ($selected_semana, $selected_servicio) {
                    if ($selected_semana) $q->where('semana_id', $selected_semana);
                    if ($selected_servicio) $q->where('servicio_id', $selected_servicio);
                    $q->with('turnoDetalle');
                }])
                ->get();
        } else {
            $usuarios = $query->get();
        }
    } else {
        $usuarios = $query->get();
    }

    $turnosRecientes = TurnoAsignado::with(['usuario', 'servicio', 'semana.mes', 'turnoDetalle'])
        ->when($selected_servicio, function($query) use ($selected_servicio) {
            return $query->where('servicio_id', $selected_servicio);
        })
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();

    return view('turnos.create', compact(
        'usuarios', 
        'servicios', 
        'semanas', 
        'turnosDisponibles', 
        'turnosRecientes', 
        'selected_usuario', 
        'selected_servicio', 
        'selected_semana'
    ));
}
    // 3. Guardar la asignación
 public function store(Request $request)
{
    $request->validate([
        'usuario_id'  => 'required|exists:usuario,id',
        'servicio_id' => 'required|exists:servicio,id',
        'semana_id'   => 'required|exists:semana,id',
        'turno_id'    => 'required|exists:turno,id',
        'dia'         => 'required|string',
    ]);

    $existeTurno = TurnoAsignado::where('usuario_id', $request->usuario_id)
        ->where('semana_id', $request->semana_id)
        ->where('dia', $request->dia)
        ->exists();

    if ($existeTurno) {
        // Si ya existe, volvemos atrás manteniendo los filtros en la URL
        return redirect()->route('turnos.create', [
            'servicio_id' => $request->servicio_id,
            'usuario_id'  => $request->usuario_id,
            'semana_id'   => $request->semana_id
        ])->with('error', 'El médico ya tiene un turno asignado para este día.');
    }

    TurnoAsignado::create([
        'usuario_id'  => $request->usuario_id,
        'servicio_id' => $request->servicio_id,
        'semana_id'   => $request->semana_id,
        'turno_id'    => $request->turno_id,
        'dia'         => $request->dia,
        'estado'      => 'Asignado',
    ]);

    // REDIRECCIÓN CORREGIDA: Volver a la vista de creación con los mismos datos
    return redirect()->route('turnos.create', [
        'servicio_id' => $request->servicio_id,
        'usuario_id'  => $request->usuario_id,
        'semana_id'   => $request->semana_id
    ])->with('success', 'Turno asignado correctamente.');
}
}