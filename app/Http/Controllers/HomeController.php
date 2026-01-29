<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio; 
use App\Models\Usuario;
use App\Models\Turno;
use App\Models\Semana;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $usuario = Auth::user(); 
        $servicios = Servicio::all();
        
        // 1. Capturamos los IDs de los filtros desde la URL
        $srvId = $request->input('servicio_id');
        $usrId = $request->input('usuario_id');
        $semId = $request->input('semana_id');

        // 2. Consulta de Usuarios con Filtros Dinámicos
        $usuarios = Usuario::where('estado', 1)
            // Filtro por Servicio
            ->when($srvId, function($q) use ($srvId) {
                return $q->where('servicio_id', $srvId); // Verifica si es 'id_servicio' o 'servicio_id'
            })
            // Filtro por Usuario específico
            ->when($usrId, function($q) use ($usrId) {
                return $q->where('id', $usrId);
            })
            // Carga de Turnos filtrados por Semana
            ->with(['turnosAsignados' => function($q) use ($semId) {
                if ($semId) {
                    $q->where('semana_id', $semId);
                }
                $q->with('turnoDetalle');
            }])
            ->get();

        // 3. Variables para los Selectores
        $turnosDisponibles = Turno::all();
        $semanas = Semana::with('mes')->get();

        // 4. Estadísticas (Usamos LIKE para evitar problemas de mayúsculas/minúsculas)
        $totalTGN = Usuario::whereHas('persona', function($q) {
            $q->where('item', 'LIKE', '%TGN%');
        })->count();

        $totalSUS = Usuario::whereHas('persona', function($q) {
            $q->where('item', 'LIKE', '%SUS%');
        })->count();

        $totalContrato = Usuario::whereHas('persona', function($q) {
            $q->where('item', 'LIKE', '%CONTRATO%');
        })->count();

        $totalPersonal = Usuario::where('estado', 1)->count();

        // 5. Retorno a la vista
        return view('home', compact(
            'usuario', 'servicios', 'usuarios', 'turnosDisponibles', 
            'semanas', 'srvId', 'usrId', 'semId', 
            'totalTGN', 'totalSUS', 'totalContrato', 'totalPersonal'
        ));
    }
}