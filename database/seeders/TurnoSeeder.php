<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnoSeeder extends Seeder
{
    
    public function run(): void
{
    DB::table('turno')->insert([
        [
            'nombre_turno' => 'Mañana',
            'hora_inicio' => '07:00:00',
            'hora_fin' => '13:00:00',
            'duracion_horas' => 6
        ],
        [
            'nombre_turno' => 'Tarde',
            'hora_inicio' => '13:00:00',
            'hora_fin' => '19:00:00',
            'duracion_horas' => 6
        ],
        [
            'nombre_turno' => 'Noche/12hras',
            'hora_inicio' => '19:00:00',
            'hora_fin' => '07:00:00',
            'duracion_horas' => 12
        ],

        [
            'nombre_turno' => 'Noche',
            'hora_inicio' => '19:00:00',
            'hora_fin' => '01:00:00',
            'duracion_horas' => 6
        ],
        [
            'nombre_turno' => 'Mañana/Tarde',
            'hora_inicio' => '07:00:00',
            'hora_fin' => '19:00:00',
            'duracion_horas' => 12
        ], // <--- Faltaba esta coma
        [
            'nombre_turno' => 'Tarde/Noche',
            'hora_inicio' => '13:00:00', // Ajusté el inicio a las 13
            'hora_fin' => '01:00:00',
            'duracion_horas' => 12
        ], // <--- Faltaba esta coma
 
        [
            'nombre_turno' => 'Mañana Temprano',
            'hora_inicio' => '04:00:00',
            'hora_fin' => '10:00:00',
            'duracion_horas' => 6
        ],
    ]);
}
    
}