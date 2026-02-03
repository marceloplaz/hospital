<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MesesSemanasSeeder extends Seeder
{
 

public function run()
{
    $meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    foreach ($meses as $index => $nombre) {
        // Asumiendo que gestion_id es el año (ej: 1 para 2025)
        $mes = \App\Models\Mes::updateOrCreate(
            ['id' => $index + 1],
            ['nombre' => $nombre, 'gestion_id' => 1] 
        );

        // Crear 4 semanas por cada mes automáticamente
        for ($i = 1; $i <= 4; $i++) {
            \App\Models\Semana::create([
                'numero' => $i,
                'mes_id' => $mes->id,
                // Puedes agregar fechas ficticias o dejarlas null si no son obligatorias
                'fecha_inicio' => now()->startOfMonth()->addWeeks($i-1),
                'fecha_fin' => now()->startOfMonth()->addWeeks($i-1)->addDays(6),
            ]);
        }
    }
}
}
