<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definición de todos los permisos
        $permisos = [
            ['nombre' => 'ver-personas', 'descripcion' => 'Ver listado y fichas del personal'],
            ['nombre' => 'editar-personas', 'descripcion' => 'Modificar datos del personal'],
            ['nombre' => 'eliminar-personas', 'descripcion' => 'Borrar registros de personal'],
            ['nombre' => 'crear-turnos', 'descripcion' => 'Crear nuevos turnos médicos'],
            ['nombre' => 'ver-turnos', 'descripcion' => 'Ver el calendario de turnos'],
            ['nombre' => 'crear-servicios', 'descripcion' => 'Registrar nuevas especialidades'],
            ['nombre' => 'editar-servicios', 'descripcion' => 'Modificar servicios existentes'],
            ['nombre' => 'añadir-usuarios', 'descripcion' => 'Crear cuentas de acceso'],
            ['nombre' => 'ver-usuarios', 'descripcion' => 'Ver lista de usuarios'],
            ['nombre' => 'editar-usuarios', 'descripcion' => 'Modificar cuentas de usuario'],
            ['nombre' => 'eliminar-usuarios', 'descripcion' => 'Eliminar cuentas de acceso'],
            ['nombre' => 'descargar-pdf', 'descripcion' => 'Generar reportes en PDF'],
            ['nombre' => 'gestionar-roles', 'descripcion' => 'Configurar permisos de roles'],
        ];

        foreach ($permisos as $permiso) {
            DB::table('permisos')->updateOrInsert(
                ['nombre' => $permiso['nombre']], 
                ['descripcion' => $permiso['descripcion']]
            );
        }

        // 2. Obtener los Roles
        $rolSuperAdmin = DB::table('roles')->where('nombre', 'SuperAdmin')->first();
        $rolAdmin = DB::table('roles')->where('nombre', 'Administrador')->first();

        // 3. ASIGNAR ROL A EDSON (ID 3)
        // Usamos 'role_id' porque en tu tabla 'usuario' la columna lleva la "e"
        if ($rolSuperAdmin) {
            DB::table('usuario')->where('id', 3)->update([
                'role_id' => $rolSuperAdmin->id 
            ]);
        }

        // 4. LIMPIAR Y ASIGNAR PERMISOS (En la tabla intermedia 'rol_permiso')
        // Aquí usamos 'rol_id' (sin la "e") para evitar el error SQL que te salió
        if ($rolSuperAdmin) {
            DB::table('rol_permiso')->where('rol_id', $rolSuperAdmin->id)->delete();
            $todosLosPermisos = DB::table('permisos')->pluck('id');
            foreach ($todosLosPermisos as $id) {
                DB::table('rol_permiso')->insert([
                    'rol_id' => $rolSuperAdmin->id,
                    'permiso_id' => $id
                ]);
            }
        }

        if ($rolAdmin) {
            DB::table('rol_permiso')->where('rol_id', $rolAdmin->id)->delete();
            $permisosFiltrados = DB::table('permisos')
                ->whereNotIn('nombre', ['eliminar-personas', 'eliminar-usuarios', 'editar-usuarios'])
                ->pluck('id');

            foreach ($permisosFiltrados as $id) {
                DB::table('rol_permiso')->insert([
                    'rol_id' => $rolAdmin->id,
                    'permiso_id' => $id
                ]);
            }
        }
        // ... (código anterior de SuperAdmin y Administrador)

// 6. Obtener IDs para los roles asistenciales
$rolMedico = DB::table('roles')->where('nombre', 'Medico')->first();
$rolEnfermera = DB::table('roles')->where('nombre', 'Enfermera')->first();

if ($rolMedico || $rolEnfermera) {
    // Definimos la lista de permisos que compartirán ambos
    $permisosAsistenciales = DB::table('permisos')
        ->whereIn('nombre', [
            'ver-personas', 
            'ver-turnos', 
            'crear-turnos', 
            'ver-usuarios', 
            'descargar-pdf',
            'crear-servicios', // Si quieres que también gestionen servicios
            'editar-servicios'
        ])
        ->pluck('id');

    // Asignar al Médico
    if ($rolMedico) {
        DB::table('rol_permiso')->where('rol_id', $rolMedico->id)->delete();
        foreach ($permisosAsistenciales as $id) {
            DB::table('rol_permiso')->insert([
                'rol_id' => $rolMedico->id,
                'permiso_id' => $id
            ]);
        }
    }

    // Asignar a la Enfermera (mismos permisos que el médico)
    if ($rolEnfermera) {
        DB::table('rol_permiso')->where('rol_id', $rolEnfermera->id)->delete();
        foreach ($permisosAsistenciales as $id) {
            DB::table('rol_permiso')->insert([
                'rol_id' => $rolEnfermera->id,
                'permiso_id' => $id
            ]);
        }
    }
}
// ... (debajo de lo que ya tenías para Médico y Enfermera)

// 7. Obtener IDs para los nuevos roles de apoyo
$rolChofer = DB::table('roles')->where('nombre', 'Chofer')->first();
$rolPersonalManual = DB::table('roles')->where('nombre', 'Personal Manual')->first();
$rolEnfermero = DB::table('roles')->where('nombre', 'Enfermero')->first(); // (Masculino, si lo creaste aparte)

$rolesSoloLectura = [$rolChofer, $rolPersonalManual, $rolEnfermero];

// Definimos los permisos básicos: Ver y Descargar
$permisosBasicos = DB::table('permisos')
    ->whereIn('nombre', [
        'ver-personas', 
        'ver-turnos', 
        'ver-usuarios', 
        'descargar-pdf'
    ])
    ->pluck('id');

foreach ($rolesSoloLectura as $rol) {
    if ($rol) {
        // Limpiamos permisos antiguos si los hubiera
        DB::table('rol_permiso')->where('rol_id', $rol->id)->delete();
        
        // Asignamos los permisos de ver y descargar
        foreach ($permisosBasicos as $permisoId) {
            DB::table('rol_permiso')->insert([
                'rol_id' => $rol->id,
                'permiso_id' => $permisoId
            ]);
        }
    }
}
    }

}