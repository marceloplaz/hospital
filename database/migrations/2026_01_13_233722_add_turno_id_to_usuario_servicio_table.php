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
        Schema::table('usuario_servicio', function (Blueprint $table) {
            // 1. Creamos la columna
            $table->unsignedBigInteger('turno_id')->nullable()->after('servicio_id');

            // 2. Creamos la relación manual apuntando a 'turno' e 'id_turno'
            $table->foreign('turno_id')
                  ->references('id_turno')
                  ->on('turno') 
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario_servicio', function (Blueprint $table) {
            $table->dropForeign(['turno_id']);
            $table->dropColumn('turno_id');
        });
    }
};