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
        Schema::create('turno_asignado', function (Blueprint $table) {
    $table->id();
    $table->foreignId('usuario_id')->constrained('usuario');
    $table->foreignId('semana_id')->constrained('semana');
    $table->foreignId('servicio_id')->constrained('servicio');
    $table->string('dia')->nullable();
    $table->string('estado')->default('Asignado');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turno_asignado');
    }
};
