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
    //Schema::table('usuario', function (Blueprint $table) {
        // Creamos la columna y la vinculamos a la tabla roles
    //    $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
    }//);
}

public function down(): void
{
    Schema::table('usuario', function (Blueprint $table) {
        $table->dropForeign(['role_id']);
        $table->dropColumn('role_id');
    });
}
};
