<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        $query = Persona::with('usuario');

        if ($request->filled('apellido')) {
            $query->where('apellidos', 'LIKE', '%' . $request->apellido . '%');
        }

        if ($request->filled('item')) {
            $query->where('item', $request->item);
        }

        $personas = $query->get();
        return view('persona.index', compact('personas'));
    }

    public function generarPDF($id)
{
    $persona = Persona::with('usuario')->findOrFail($id);
    
    $pdf = Pdf::loadView('persona.pdf', compact('persona'));
    
    // Retornar el PDF para descargar o ver en navegador
    return $pdf->stream('Ficha_' . $persona->apellidos . '.pdf');
}
    public function create()
    {
        $usuarios = Usuario::all();
        $roles = DB::table('roles')->get(); // NUEVO: Obtener roles para el select
        return view('persona.create', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'usuario_id' => 'required|exists:usuario,id|unique:personas,usuario_id',
            'tipo_trabajador' => 'required|string', // Aquí llega el nombre del rol
            'item' => 'required|in:Item TGN,Item SUS,Contrato', 
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|string',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'nacionalidad' => 'nullable|string',
        ]);

        // En PersonaController.php
DB::transaction(function () use ($validated, $request) {
    // 1. Crear la persona
    $persona = Persona::create($validated);

    // 2. Buscar el rol (usamos una búsqueda que ignore mayúsculas/minúsculas por seguridad)
    $rol = DB::table('roles')
             ->where('nombre', 'LIKE', $request->tipo_trabajador)
             ->first();

    if ($rol) {
        // Asignamos el ID del rol encontrado al usuario
        Usuario::where('id', $request->usuario_id)->update(['rol_id' => $rol->id]);
    } else {
        // Opcional: Asignar un rol por defecto (ID 8 es 'Personal' según tu imagen)
        Usuario::where('id', $request->usuario_id)->update(['rol_id' => 8]);
    }
});

        return redirect()->route('personas.index')->with('success', 'Personal registrado y rol asignado.');
    }

    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        $usuarios = Usuario::all();
        $roles = DB::table('roles')->get(); // NUEVO: Obtener roles para el select
        return view('persona.edit', compact('persona', 'usuarios', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'usuario_id' => 'required|exists:usuario,id|unique:personas,usuario_id,' . $id,
            'tipo_trabajador' => 'required|string',
            'item' => 'required|in:Item TGN,Item SUS,Contrato', 
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|string',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'nacionalidad' => 'nullable|string',
        ]);

        DB::transaction(function () use ($persona, $validated, $request) {
            // 1. Actualizar persona
            $persona->update($validated);

            // 2. NUEVO: Sincronizar rol con el usuario
            $rol = DB::table('roles')->where('nombre', $request->tipo_trabajador)->first();
            if ($rol) {
                Usuario::where('id', $request->usuario_id)->update(['rol_id' => $rol->id]);
            }
        });

        return redirect()->route('personas.index')->with('success', 'Datos y rol actualizados correctamente.');
    }

    // En PersonaController.php

public function show($id)
{
    // Buscamos solo la persona. Si existe un usuario vinculado, Laravel lo traerá 
    // automáticamente si lo llamas en la vista, pero no forzamos la carga del "rol".
    $persona = Persona::findOrFail($id);

    return view('persona.show', compact('persona'));
}
    
    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);

        DB::transaction(function () use ($persona) {
            $usuarioId = $persona->usuario_id;
            $persona->delete();

            if ($usuarioId) {
                Usuario::where('id', $usuarioId)->delete();
            }
        });

        return redirect()->route('personas.index')->with('success', 'Personal y cuenta eliminados.');
    }
}