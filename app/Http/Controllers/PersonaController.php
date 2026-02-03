<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::with('usuario')->get(); 
        return view('persona.index', compact('personas'));
    }

    public function create()
    {
        return view('persona.create');
    }

    public function store(Request $request)
    {
        // 1. Validamos todos los campos del formulario
        $request->validate([
            'nombres'          => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'ci'               => 'required|unique:personas,ci',
            'email'            => 'required|email|unique:usuario,email',
            'tipo_trabajador'  => 'required',
            'genero'           => 'required',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:255',
            'nacionalidad'     => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'tipo_salario'     => 'required|in:TGN,SUS,Contrato',
            'salario'          => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 2. Crear el Usuario de acceso con el email manual y CI como clave
            $nuevoUsuario = Usuario::create([
                'nombre'   => $request->nombres . ' ' . $request->apellidos,
                'email'    => $request->email,
                'password' => Hash::make($request->ci), 
                'estado'   => 1
            ]);

            // 3. Crear la Persona con la lista COMPLETA de campos
            Persona::create([
                'nombres'          => $request->nombres,
                'apellidos'        => $request->apellidos,
                'ci'               => $request->ci,
                'tipo_trabajador'  => $request->tipo_trabajador,
                'genero'           => $request->genero,
                'telefono'         => $request->telefono,
                'direccion'        => $request->direccion,
                'nacionalidad'     => $request->nacionalidad ?? 'boliviana',
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'tipo_salario'     => $request->tipo_salario,
                'salario'          => (int) $request->salario, // Entero
                'usuario_id'       => $nuevoUsuario->id,
            ]);

            DB::commit();
            return redirect()->route('personas.index')->with('success', 'Personal registrado correctamente con todos sus datos.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function toggleEstado($id)
    {
        $persona = Persona::findOrFail($id);
        
        if ($persona->usuario) {
            $nuevoEstado = $persona->usuario->estado == 1 ? 0 : 1;
            $persona->usuario->update(['estado' => $nuevoEstado]);
            
            $mensaje = $nuevoEstado == 1 ? 'Cuenta activada.' : 'Cuenta desactivada.';
            return redirect()->back()->with('success', $mensaje);
        }

        return redirect()->back()->with('error', 'Esta persona no tiene un usuario asignado.');
    }
    public function asignar(Request $request)
{
    $request->validate([
        'persona_id' => 'required',
        'email' => 'required|email|unique:usuario,email',
        'password' => 'required'
    ]);

    $persona = Persona::find($request->persona_id);
    
    $usuario = Usuario::create([
        'nombre' => $persona->nombres . ' ' . $persona->apellidos,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'estado' => 1
    ]);

    $persona->update(['usuario_id' => $usuario->id]);

    return redirect()->back()->with('success', 'Cuenta de acceso creada con éxito.');
}
}