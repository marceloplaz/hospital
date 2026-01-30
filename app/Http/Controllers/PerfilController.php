<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    // El constructor debe ir dentro de la clase
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();

        // Validamos que el usuario esté logueado antes de pedir la relación
        if (!$usuario) {
            return redirect()->route('login');
        }

        // Cargamos la persona relacionada. 
        // IMPORTANTE: Asegúrate de que en el modelo Usuario exista la función persona()
        $persona = $usuario->persona ?: new Persona();

        return view('perfil.index', compact('usuario', 'persona'));
    }

    public function update(Request $request)
    {
        $usuario = Auth::user();
        
        $request->validate([
            'ci' => 'required',
            'telefono' => 'required',
            'direccion' => 'required'
        ]);

        
        // En PerfilController.php
        $persona = Persona::updateOrCreate(
        ['usuario_id' => $usuario->id],
            [
            'nombres'          => $usuario->nombre,
            'apellidos'        => $request->apellidos,
            'ci'               => $request->ci,
            'telefono'         => $request->telefono,
            'direccion'        => $request->direccion,
            'genero'           => $request->genero,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'nacionalidad'     => $request->nacionalidad,
            ]
        );

        return redirect()->back()->with('success', 'Tus datos personales se han actualizado.');
    }
}