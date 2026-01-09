<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash; // Asegúrate de importar esto arriba
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UsuarioServicio;
use App\Models\Servicio;
use App\Models\User; // Asegúrate de que tu modelo de usuario se llame User

class ServicioController extends Controller
{
    // 1. LISTAR TODOS LOS SERVICIOS
    public function index()
    {
        $servicio = DB::select("SELECT * FROM servicio");
        return view("servicio.lista", compact("servicio"));
    }

    // 2. GUARDAR NUEVO SERVICIO
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

    //esta es para la funcion hass para guardar un usuario
   // Asegúrate de importar estas clases al inicio del archivo:
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;

public function storeUsuario(Request $request) 
{
    // Validar es fundamental para evitar que campos lleguen vacíos
    $request->validate([
        'nombre'   => 'required|string|max:255',
        'email'    => 'required|email|unique:usuario,email',
        'password' => 'required|min:6',
    ]);

    User::create([
        'nombre'   => $request->nombre,
        'email'    => $request->email,
        'password' => $request->password, // Nota: Si en el modelo User tienes 'password' => 'hashed', NO hace falta Hash::make aquí.
    ]);

    return redirect("/login")->with('success', 'Usuario creado. ¡Ya puedes entrar!');
}


    // Cambia "asignarServicio" por "funAsignar_usuario_servicio"

 // Esta es la función que pide tu error (GET)
public function funAsignar_usuario_servicio($id)
{
    // Buscamos el servicio. Usamos 'nombre' porque es lo que usas en tu vista.
    $servicio = \App\Models\Servicio::with('usuarios')->findOrFail($id);
    
    // Obtenemos todos los usuarios para el selector
    $usuarios = \App\Models\User::all(); 

    return view('servicio.Asignar_usuario_servicio', compact('servicio', 'usuarios'));
}

// Esta es la función para procesar el formulario (POST)
public function funAsignar_usuario_servicioBD(Request $request, $id)
{
    // 1. Validar los datos antes de insertar
    $request->validate([
        'usuario_id' => 'required|exists:users,id', // Verifica que el usuario exista
        'descripcion_usuario_servicio' => 'required|string|max:255',
        'estado' => 'required'
    ]);

    // 2. Usar el modelo importado (asegúrate de tener 'use App\Models\UsuarioServicio;' arriba)
    UsuarioServicio::create([
        'fecha_ingreso' => now()->toDateString(), // Formato YYYY-MM-DD para MySQL
        'descripcion_usuario_servicio' => $request->descripcion_usuario_servicio,
        'usuario_id' => $request->usuario_id,
        'servicio_id' => $id,
        'estado' => $request->estado,
    ]);

    // 3. Redirigir con un mensaje que AdminLTE pueda mostrar
    return redirect()->back()->with('success', '¡Personal asignado al servicio con éxito!');
}
 

    // 4. GUARDAR LA ASIGNACIÓN EN LA TABLA PIVOTE
    public function storeUsuarioServicio(Request $request, $id)
    {
        UsuarioServicio::create([
            'fecha_ingreso' => now(),
            'descripcion_usuario_servicio' => $request->descripcion_usuario_servicio,
            'usuario_id' => $request->usuario_id,
            'servicio_id' => $id,
            'estado' => $request->estado,
        ]);

        return redirect()->back()->with('success', 'Usuario asignado correctamente');
    }

    // 5. MOSTRAR LOS USUARIOS DE UN SERVICIO ESPECÍFICO
    public function show($id)
    {
        // Buscamos el servicio y cargamos sus usuarios vinculados
        $servicio = Servicio::with('usuarios')->findOrFail($id);

        return view('servicio.show', compact('servicio'));
    }

    public function create()
{
    // Esta función solo devuelve la vista del formulario
    return view('servicio.crear');
}
} // <--- ESTA LLAVE DEBE SER LA ÚNICA AL FINAL DEL ARCHIVO