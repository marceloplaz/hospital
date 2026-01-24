<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
                'nombre_turno' => 'Noche Corta',
                'hora_inicio' => '19:00:00',
                'hora_fin' => '01:00:00',
                'duracion_horas' => 6
            ],
            [
                'nombre_turno' => 'Noche Larga',
                'hora_inicio' => '19:00:00',
                'hora_fin' => '07:00:00',
                'duracion_horas' => 12
            ],
            [
                'nombre_turno' => 'Mañana Temprano',
                'hora_inicio' => '4:00:00',
                'hora_fin' => '10:00:00',
                'duracion_horas' => 6
            ],
        ]);
    }
}