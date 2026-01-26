<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesYPermisosSeeder extends Seeder
{
    public function run(): void
    {
        // Lista completa de roles solicitados
        $roles = [
            ['nombre' => 'SuperAdmin', 'descripcion' => 'Control total'],
            ['nombre' => 'Médico', 'descripcion' => 'Personal médico'],
            ['nombre' => 'Enfermero', 'descripcion' => 'Enfermería masculina'],
            ['nombre' => 'Enfermera', 'descripcion' => 'Enfermería femenina'],
            ['nombre' => 'Administrativo', 'descripcion' => 'Personal de oficina'],
            ['nombre' => 'Personal', 'descripcion' => 'Personal de apoyo'],
            ['nombre' => 'Chofer', 'descripcion' => 'Conductor de ambulancia'],
            ['nombre' => 'Manual', 'descripcion' => 'Mantenimiento y limpieza'],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(
                ['nombre' => $rol['nombre']], 
                [
                    'descripcion' => $rol['descripcion'], 
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}