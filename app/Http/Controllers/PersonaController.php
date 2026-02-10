<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Servicio;
use App\Models\Mes;
use App\Models\Semana;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::with('usuario')->get(); 
        return view('persona.index', compact('personas'));
    }

    public function create(Request $request)
    {
        // Seguridad: Solo admin, Enfermero o el ID 1 pueden crear
        if (!auth()->user()->hasAnyRole(['admin', 'Enfermero']) && auth()->user()->id != 1) {
            abort(403, 'No tienes permiso para crear personal.');
        }

        $servicios = Servicio::all();
        $meses = Mes::all();
        $semanas = $request->mes_id ? Semana::where('mes_id', $request->mes_id)->get() : collect();

        return view('persona.create', compact('servicios', 'meses', 'semanas'));
    }

    public function store(Request $request)
    {
        // 1. Validar los datos
        $request->validate([
            'nombres'         => 'required|string|max:255',
            'apellidos'       => 'required|string|max:255',
            'ci'              => 'required|string|unique:personas,ci',
            'email'           => 'required|email|unique:usuario,email',
            'tipo_trabajador' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // 2. Crear el Usuario en la tabla 'usuario'
            $nuevoUsuario = Usuario::create([
                'nombre'   => $request->nombres . ' ' . $request->apellidos,
                'email'    => $request->email,
                'password' => Hash::make($request->ci), // Clave inicial es su CI
                'estado'   => 1,
            ]);

            // 3. ASIGNAR ROL (Importante para que pueda entrar al sistema)
            // Esto asume que tienes los roles 'Médico', 'Enfermero', etc. creados
            $nuevoUsuario->assignRole($request->tipo_trabajador);

            // 4. Crear la Persona y vincularla al usuario
            Persona::create([
                'nombres'         => $request->nombres,
                'apellidos'       => $request->apellidos,
                'ci'              => $request->ci,
                'usuario_id'      => $nuevoUsuario->id, 
                'tipo_trabajador' => $request->tipo_trabajador,
                'genero'          => $request->genero,
                'telefono'        => $request->telefono,
                'direccion'       => $request->direccion,
                'tipo_salario'    => $request->tipo_salario,
                'salario'         => $request->salario ?? 0,
                'nacionalidad'    => $request->nacionalidad ?? 'Boliviana',
                'fecha_nacimiento'=> $request->fecha_nacimiento,
            ]);

            DB::commit();
            return redirect()->route('personas.index')
                             ->with('success', '¡Personal registrado y cuenta de acceso activada!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }
}