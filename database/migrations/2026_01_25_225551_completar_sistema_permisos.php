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
    // Solo crea 'permisos' si NO existe ya
    if (!Schema::hasTable('permisos')) {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
    }

    // Solo crea la pivot si NO existe ya
    if (!Schema::hasTable('rol_permiso')) {
        Schema::create('rol_permiso', function (Blueprint $table) {
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permiso_id')->constrained('permisos')->onDelete('cascade');
            $table->primary(['rol_id', 'permiso_id']);
        });
    }

    // Verificar la columna en 'usuario'
    Schema::table('usuario', function (Blueprint $table) {
        if (!Schema::hasColumn('usuario', 'rol_id')) {
            $table->foreignId('rol_id')->nullable()->after('id')->constrained('roles')->onDelete('set null');
        }
    });
}
};
