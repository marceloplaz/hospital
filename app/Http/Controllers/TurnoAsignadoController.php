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
            ->with(['turnosAsignados' => function($query) use ($semanaId) {
                if ($semanaId) {
                    $query->where('semana_id', $semanaId);
                }
                $query->with('turnoDetalle');
            }])
            ->get();

        return view('turnos.index', compact('turnos', 'servicios', 'semanas', 'servicioSeleccionado', 'semanaSeleccionada'));
    }

    // 2. Mostrar formulario para crear
    public function create(Request $request)
    {
        $servicios = Servicio::all();
        $semanas = Semana::with('mes')->get();
        $turnosDisponibles = Turno::all();

        $selected_servicio = $request->get('servicio_id');
        $selected_usuario = $request->get('usuario_id');
        $selected_semana = $request->get('semana_id');

        // Carga base de usuarios
        $usuarios = Usuario::where('estado', 1)->get();

        if ($selected_servicio) {
            $servicioEncontrado = Servicio::find($selected_servicio);
            if ($servicioEncontrado) {
                // Filtramos por la relación si existe
                $usuariosDelServicio = $servicioEncontrado->usuarios()
                    ->where('usuario.estado', 1)
                    ->get();
                
                if ($usuariosDelServicio->isNotEmpty()) {
                    $usuarios = $usuariosDelServicio;
                }
            }
        }

        $turnosRecientes = TurnoAsignado::with(['usuario', 'servicio', 'semana.mes', 'turnoDetalle'])
            ->when($selected_servicio, function($query) use ($selected_servicio) {
                return $query->where('servicio_id', $selected_servicio);
            })
            ->orderBy('id', 'desc') // Si tu tabla turno_asignado no tiene 'id', usa 'id_turno_asignado'
            ->take(10)
            ->get();

        return view('turnos.create', compact(
            'usuarios', 'servicios', 'semanas', 'turnosDisponibles', 
            'turnosRecientes', 'selected_usuario', 'selected_servicio', 'selected_semana'
        ));
    }

    // 3. Guardar la asignación (CORREGIDO PARA EL ERROR 1054)
    public function store(Request $request)
    {
        // AJUSTE: He cambiado 'id' por el nombre probable de tus columnas.
        // Si en tu base de datos siguen siendo 'id', déjalos, pero el error dice que en 'turno' no existe.
        $request->validate([
            'usuario_id'  => 'required|exists:usuario,id',
            'servicio_id' => 'required|exists:servicio,id',
            'semana_id'   => 'required|exists:semana,id',
            'turno_id'    => 'required|exists:turno,id_turno', // <--- CAMBIADO AQUÍ
            'dia'         => 'required|string',
        ]);

        $existeTurno = TurnoAsignado::where('usuario_id', $request->usuario_id)
            ->where('semana_id', $request->semana_id)
            ->where('dia', $request->dia)
            ->exists();

        if ($existeTurno) {
            return redirect()->route('turnos.create', [
                'servicio_id' => $request->servicio_id,
                'usuario_id'  => $request->usuario_id,
                'semana_id'   => $request->semana_id
            ])->with('error', 'El médico ya tiene un turno asignado para este día.');
        }

        // Usamos Eloquent para crear el registro
        $nuevaAsignacion = new TurnoAsignado();
        $nuevaAsignacion->usuario_id = $request->usuario_id;
        $nuevaAsignacion->servicio_id = $request->servicio_id;
        $nuevaAsignacion->semana_id = $request->semana_id;
        $nuevaAsignacion->turno_id = $request->turno_id;
        $nuevaAsignacion->dia = $request->dia;
        $nuevaAsignacion->estado = 'Asignado';
        $nuevaAsignacion->save();

        return redirect()->route('turnos.create', [
            'servicio_id' => $request->servicio_id,
            'usuario_id'  => $request->usuario_id,
            'semana_id'   => $request->semana_id
        ])->with('success', 'Turno asignado correctamente.');
    }
}