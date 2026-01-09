<?php

namespace App\Http\Controllers; // Debe estar exactamente así

use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de importar el modelo User

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Cargamos al usuario con sus servicios
        $usuario = User::with('servicios')->find(auth()->id());
        
        return view('home', compact('usuario'));
    }
}