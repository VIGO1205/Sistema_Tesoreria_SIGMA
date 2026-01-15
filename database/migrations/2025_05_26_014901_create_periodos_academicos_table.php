<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estado_periodo_academico', function (Blueprint $table) {
            $table->increments('id_estado_periodo_academico');
            $table->string('nombre', 255);
        });

        Schema::create('periodos_academicos', function (Blueprint $table) {
            $table->increments('id_periodo_academico');
            $table->string('nombre', 255);
            $table->unsignedInteger('id_estado_periodo_academico');

            $table->foreign('id_estado_periodo_academico')
                ->references('id_estado_periodo_academico')
                ->on('estado_periodo_academico');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos_academicos');
        Schema::dropIfExists('estado_periodo_academico');
    }
};