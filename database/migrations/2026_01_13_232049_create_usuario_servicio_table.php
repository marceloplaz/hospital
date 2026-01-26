<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('usuario_servicio', function (Blueprint $table) {
            $table->id();
            
            // Definición explícita para evitar errores de restricción
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('servicio_id');

            // Relación con la tabla 'usuario'
            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('usuario') 
                  ->onDelete('cascade');

            // Relación con la tabla 'servicio'
            $table->foreign('servicio_id')
                  ->references('id')
                  ->on('servicio')
                  ->onDelete('cascade');
            
            // Campos de datos
            $table->string('descripcion_usuario_servicio');
            $table->string('estado')->default('Activo');
            $table->date('fecha_ingreso'); 
            
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('usuario_servicio');
    }
}; // <--- EL PUNTO Y COMA AQUÍ ES LA SOLUCIÓN