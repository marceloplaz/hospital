<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'CIRUGIA MUJERES', 'descripcion' => 'Área de cirugías para pacientes femeninas', 'cantidad_pacientes' => 10],
            ['nombre' => 'CIRUGIA VARONES', 'descripcion' => 'Área de cirugías para pacientes masculinos', 'cantidad_pacientes' => 10],
            ['nombre' => 'MEDICINA MUJERES', 'descripcion' => 'Medicina interna femenina', 'cantidad_pacientes' => 15],
            ['nombre' => 'MEDICINA VARONES', 'descripcion' => 'Medicina interna masculina', 'cantidad_pacientes' => 15],
            ['nombre' => 'GINECOLOGIA', 'descripcion' => 'Salud reproductiva femenina', 'cantidad_pacientes' => 20],
            ['nombre' => 'MATERNIDAD', 'descripcion' => 'Atención de partos y post-parto', 'cantidad_pacientes' => 20],
            ['nombre' => 'QUIROFANO', 'descripcion' => 'Salas de operaciones', 'cantidad_pacientes' => 5],
            ['nombre' => 'HEMODIALISIS', 'descripcion' => 'Tratamiento de insuficiencia renal', 'cantidad_pacientes' => 12],
            ['nombre' => 'PEDIATRIA', 'descripcion' => 'Atención médica infantil', 'cantidad_pacientes' => 25],
            ['nombre' => 'NEONATOLOGIA', 'descripcion' => 'Cuidado de recién nacidos', 'cantidad_pacientes' => 10],
            ['nombre' => 'QUEMADOS', 'descripcion' => 'Unidad de cuidados para quemaduras', 'cantidad_pacientes' => 8],
            ['nombre' => 'COLOPROCTOLOGIA', 'descripcion' => 'Especialidad en colon y recto', 'cantidad_pacientes' => 6],
            ['nombre' => 'RAYOS X', 'descripcion' => 'Servicios de radiología e imagenología', 'cantidad_pacientes' => 0],
            ['nombre' => 'EMERGENCIAS', 'descripcion' => 'Atención de urgencias médicas', 'cantidad_pacientes' => 30],
            ['nombre' => 'NUTRICION', 'descripcion' => 'Planificación dietética y nutrición', 'cantidad_pacientes' => 0],
            ['nombre' => 'PATOLOGIA', 'descripcion' => 'Laboratorio de análisis de tejidos', 'cantidad_pacientes' => 0],
            ['nombre' => 'UTI-TERAPIA INTENSIVA', 'descripcion' => 'Cuidados críticos e intensivos', 'cantidad_pacientes' => 10],
            ['nombre' => 'CAMILLEROS', 'descripcion' => 'Servicio de traslado de pacientes', 'cantidad_pacientes' => 0],
            ['nombre' => 'LAVANDERIA', 'descripcion' => 'Gestión de textiles hospitalarios', 'cantidad_pacientes' => 0],
        ];

        foreach ($servicios as $servicio) {
            Servicio::updateOrCreate(['nombre' => $servicio['nombre']], $servicio);
        }
    }
}

