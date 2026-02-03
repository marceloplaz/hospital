<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario; // Asegúrate de que tu modelo se llame así
use Illuminate\Support\Facades\Hash; // Necesario para encriptar la clave

class PersonaController extends Controller
{
    public function storeUsuario(Request $request)
    {
        // 1. Validar datos
        $request->validate([
            'email' => 'required|email|unique:usuario,email',
            'password' => 'required|min:6',
            'persona_id' => 'required|exists:personas,id' 
        ]);

        // 2. Crear el Usuario
        // Nota: Asegúrate de que 'nombre', 'email', etc., estén en el $fillable de Usuario.php
        $nuevoUsuario = Usuario::create([
            'nombre'   => $request->nombre_usuario,
            'email'    => $request->email,
            'password' => Hash::make($request->password), 
            'estado'   => 1
        ]);

        // 3. Vincular a la Persona
        $persona = Persona::find($request->persona_id);
        $persona->usuario_id = $nuevoUsuario->id;
        $persona->save();

        return redirect()->back()->with('success', '¡Cuenta de usuario creada y asignada a ' . $persona->nombres . '!');
    }
}