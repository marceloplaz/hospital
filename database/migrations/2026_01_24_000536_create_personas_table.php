<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('personas', function (Blueprint $table) {
        // Esto transforma el ENUM rígido en un STRING flexible
        $table->string('tipo_trabajador')->change();
    });
}

public function down(): void
{
    Schema::table('personas', function (Blueprint $table) {
        // Por si alguna vez necesitas volver al enum (opcional)
        $table->enum('tipo_trabajador', [
            'Médico', 'Enfermera', 'Administrativo', 'Chofer', 'Manual', 'Enfermero', 'Personal'
        ])->change();
    });
}
   
};
