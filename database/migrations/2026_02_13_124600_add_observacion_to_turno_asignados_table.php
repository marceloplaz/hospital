<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::table('turno_asignado', function (Blueprint $table) {
        $table->string('observacion')->nullable()->after('usuario_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turno_asignados', function (Blueprint $table) {
            //
        });
    }
};
