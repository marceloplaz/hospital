<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalBaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles con 'updateOrInsert' para evitar errores de duplicidad
        $roles = ['Administrador', 'Médico', 'Enfermería'];
        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(
                ['nombre' => $rol], // Lo que busca para identificarlo
                ['updated_at' => now()] // Lo que actualiza si ya existe
            );
        }

        // 2. Turnos inteligentes
        $turnos = [
            ['nombre_turno' => 'Mañana', 'hora_inicio' => '07:00:00', 'hora_fin' => '13:00:00', 'duracion_horas' => 6],
            ['nombre_turno' => 'Tarde', 'hora_inicio' => '13:00:00', 'hora_fin' => '19:00:00', 'duracion_horas' => 6],
            ['nombre_turno' => 'Noche', 'hora_inicio' => '19:00:00', 'hora_fin' => '07:00:00', 'duracion_horas' => 12],
        ];
        foreach ($turnos as $turno) {
            DB::table('turno')->updateOrInsert(
                ['nombre_turno' => $turno['nombre_turno']], 
                $turno
            );
        }

       // 3. Servicios inteligentes (Corregido)
$servicios = [
    ['nombre' => 'Emergencias', 'descripcion' => 'Atención inmediata'], // Cambié 'nombre_servicio' por 'nombre'
    ['nombre' => 'Pediatría', 'descripcion' => 'Atención infantil'],
    ['nombre' => 'Ginecología', 'descripcion' => 'Salud femenina'],
];

foreach ($servicios as $servicio) {
    DB::table('servicio')->updateOrInsert(
        ['nombre' => $servicio['nombre']], // Usa el nombre de columna que viste en Tinker
        $servicio
    );
}
    }
}