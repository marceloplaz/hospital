<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // Usamos tu modelo Usuario
use App\Models\Persona;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. Obtenemos al usuario autenticado con sus relaciones
        // Nota: Asegúrate de usar el modelo correcto (Usuario o User)
        $usuario = auth()->user();

        // 2. Conteos para las tarjetas del Dashboard
        $totalTGN = Persona::where('item', 'Item TGN')->count();
        $totalSUS = Persona::where('item', 'Item SUS')->count();
        $totalContrato = Persona::where('item', 'Contrato')->count();
        $totalPersonal = Persona::count();

        // 3. Enviamos TODO a la vista en un solo compact
        return view('home', compact(
            'usuario', 
            'totalTGN', 
            'totalSUS', 
            'totalContrato', 
            'totalPersonal'
        ));
    }
}