<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('turno', function (Blueprint $table) {
            // Definimos id_turno como la llave primaria
            $table->id('id_turno'); 
            
            // Nombre del turno (Mañana, Tarde, Noche, etc.)
            $table->string('nombre_turno', 50);
            
            // Horas de inicio y fin (Tipo TIME)
            $table->time('hora_inicio');
            $table->time('hora_fin');
            
            // Cantidad de horas (8, 6, 7, 12)
            $table->integer('duracion_horas');
            
            // Usamos timestamps solo si quieres llevar registro de creación/edición
            // Si tu base de datos es simple, puedes comentarlo
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turno');
    }
};