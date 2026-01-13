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
        Schema::table('solicitudes_traslado', function (Blueprint $table) {
            $table->string('tipo_solicitud', 50)->default('regular')->after('estado')->comment('Tipo de solicitud: regular o excepcional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes_traslado', function (Blueprint $table) {
            $table->dropColumn('tipo_solicitud');
        });
    }
};
