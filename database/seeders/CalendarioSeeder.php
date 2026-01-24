<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gestion;
use App\Models\Mes;
use App\Models\Semana;
use Carbon\Carbon;

class CalendarioSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear la Gestión (Año)
        $gestion = Gestion::create(['anio' => 2026]);

        // 2. Definimos el inicio y fin del año calendario
        // Empezamos en el primer lunes que corresponda al año 2026
        $fecha = Carbon::create(2026, 1, 1)->startOfWeek(); 
        $finDeAnio = Carbon::create(2026, 12, 31)->endOfYear();

        $numeroSemana = 1;

        // 3. Diccionario de meses para no duplicarlos
        $mesesCreados = [];

        // 4. Bucle lineal: Recorre semana a semana sin importar el salto de mes
        while ($fecha <= $finDeAnio) {
            $inicioSemana = $fecha->copy()->startOfWeek();
            $finSemana = $fecha->copy()->endOfWeek();

            // Determinamos a qué mes pertenece el INICIO de esta semana
            $nombreMes = $inicioSemana->translatedFormat('F'); // Ejemplo: "January"

            // Si el mes no ha sido creado en la DB para esta gestión, lo creamos
            if (!isset($mesesCreados[$nombreMes])) {
                $nuevoMes = Mes::create([
                    'nombre' => ucfirst($nombreMes),
                    'gestion_id' => $gestion->id
                ]);
                $mesesCreados[$nombreMes] = $nuevoMes->id;
            }

            // 5. Crear la semana vinculada al mes correspondiente
            Semana::create([
                'numero' => $numeroSemana,
                'fecha_inicio' => $inicioSemana->format('Y-m-d'),
                'fecha_fin' => $finSemana->format('Y-m-d'),
                'mes_id' => $mesesCreados[$nombreMes]
            ]);

            // Avanzamos exactamente 7 días para la siguiente iteración
            $fecha->addWeek();
            $numeroSemana++;
        }
    }
}