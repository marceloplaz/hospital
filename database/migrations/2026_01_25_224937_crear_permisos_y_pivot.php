<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // 1. Tabla de Permisos individuales
    Schema::create('permisos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre')->unique(); // Ej: 'crear-personal', 'eliminar-personal'
        $table->string('descripcion')->nullable();
        $table->timestamps();
    });

    // 2. Tabla Intermedia (Pivot) para asignar permisos a roles
    Schema::create('rol_permiso', function (Blueprint $table) {
        $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
        $table->foreignId('permiso_id')->constrained('permisos')->onDelete('cascade');
        $table->primary(['rol_id', 'permiso_id']); // Llave primaria compuesta
    });

    // 3. Agregar la relación en la tabla de usuarios (si no la tienes)
    // Asumiendo que tu tabla se llama 'usuario'
    Schema::table('usuario', function (Blueprint $table) {
        if (!Schema::hasColumn('usuario', 'rol_id')) {
            $table->foreignId('rol_id')->nullable()->constrained('roles')->onDelete('set null');
        }
    });
}/**
     * Run the migrations.
    
    */
    };

