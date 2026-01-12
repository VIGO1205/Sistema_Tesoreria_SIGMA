<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->string('clave', 63)->primary();
            $table->string('valor', 255)->nullable();
            $table->timestamps();
        });

        // Insertar configuración inicial
        DB::table('configuracion')->insert([
            'clave' => 'ID_PERIODO_ACADEMICO_ACTUAL',
            'valor' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};