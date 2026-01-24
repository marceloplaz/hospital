<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('personas', function (Blueprint $table) {
        $table->id();
        $table->string('nombres');
        $table->string('apellidos');
        
        // Debe decir 'usuario_id' y apuntar a 'usuario'
        $table->foreignId('usuario_id')->constrained('usuario')->onDelete('cascade');
        
        $table->enum('tipo_trabajador', [
            'Médico', 'Enfermero', 'Administrativo', 'Chofer', 'Manual'
        ])->default('Médico'); 

        $table->date('fecha_nacimiento')->nullable();
        $table->string('genero')->nullable();
        $table->string('telefono')->nullable();
        $table->string('direccion')->nullable();
        $table->string('nacionalidad')->nullable();
        $table->timestamps();
    });
}
   
};
