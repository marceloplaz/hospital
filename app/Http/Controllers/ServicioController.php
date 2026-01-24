<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UsuarioServicio;
use App\Models\Servicio;
use App\Models\User;

class ServicioController extends Controller
{
    // 1. LISTAR TODOS LOS SERVICIOS
    public function index()
    {
        $servicio = DB::select("SELECT * FROM servicio");
        return view("servicio.lista", compact("servicio"));
    }

    // 2. FORMULARIO PARA CREAR SERVICIO
    public function create()
    {
        return view('servicio.crear');
    }

    // 3. GUARDAR NUEVO SERVICIO
    public function store(Request $request)
    {
        DB::insert(
            "INSERT INTO servicio (nombre, descripcion, cantidad_pacientes)
             VALUES (?, ?, ?)",
            [
                $request->nombre,
                $request->descripcion,
                $request->cantidad_pacientes
            ]
        );

        return redirect("/servicio")->with('success', 'Servicio agregado correctamente');
    }

    // 4. GUARDAR UN USUARIO NUEVO (LOGIN/REGISTRO)
    public function storeUsuario(Request $request) 
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect("/login")->with('success', 'Usuario creado. ¡Ya puedes entrar!');
    }

    // 5. VER PANTALLA DE ASIGNACIÓN (GET)
    public function funAsignar_usuario_servicio($id)
    {
        $servicio = Servicio::with('usuarios')->findOrFail($id);
        $usuarios = User::all(); 

        return view('servicio.Asignar_usuario_servicio', compact('servicio', 'usuarios'));
    }

    // 6. PROCESAR LA ASIGNACIÓN (POST)
    public function funAsignar_usuario_servicioBD(Request $request, $id)
    {
        $request->validate([
            'usuario_id' => 'required',
            'descripcion_usuario_servicio' => 'required|string',
            'estado' => 'required'
        ]);

        // Buscamos el servicio
        $servicio = Servicio::findOrFail($id);
        
        // Usamos attach para guardar en la tabla pivote 'usuario_servicio'
        // Esto es mucho más limpio y evita errores de sintaxis
        $servicio->usuarios()->attach($request->usuario_id, [
            'descripcion_usuario_servicio' => $request->descripcion_usuario_servicio,
            'estado' => $request->estado,
            'fecha_ingreso' => now()
        ]);

        return redirect()->back()->with('success', '¡Personal asignado con éxito!');
    }

    // 7. MOSTRAR DETALLES DE UN SERVICIO
    public function show($id)
    {
        $servicio = Servicio::with('usuarios')->findOrFail($id);
        return view('servicio.show', compact('servicio'));
    }
}