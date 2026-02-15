<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // En tus queries se ve que usas Usuario
use App\Models\TurnoAsignado;
use App\Models\Semana;
use Carbon\Carbon;

class HomeController extends Controller
{
public function index()
{
    $userId = auth()->id();
    $usuario = \App\Models\Usuario::with('servicio')->find($userId);

    // 1. Obtenemos la semana más reciente programada para este usuario
    $ultimaAsignacion = \App\Models\TurnoAsignado::where('usuario_id', $userId)
                        ->orderBy('semana_id', 'desc')
                        ->first();

    $misTurnos = collect();
    $primerServicio = $usuario->servicio->first();
    $servicioId = $primerServicio ? $primerServicio->id : null;

    if ($ultimaAsignacion) {
        $semanaActual = \App\Models\Semana::find($ultimaAsignacion->semana_id);
        
        // 2. Traemos TODOS los turnos de esa semana
        // Agrupamos por día para manejar múltiples turnos el mismo día (si aplica)
        $misTurnos = \App\Models\TurnoAsignado::where('usuario_id', $userId)
            ->where('semana_id', $semanaActual->id)
            ->with(['turnoDetalle', 'servicio']) // Importante cargar el servicio
            ->get()
            ->groupBy(function ($item) {
                return str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], strtolower($item->dia));
            });
    }
  
    $hoyNombreEs = ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd'));

    return view('home', compact('usuario', 'misTurnos', 'semanaActual', 'hoyNombreEs'));
}
}