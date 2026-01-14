<?php

use App\Models\EstadoEtapaCronogramaPeriodoAcademico;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabla TipoEtapaPeriodoAcademico
        Schema::create('tipo_etapa_cronograma_periodo_academico', function (Blueprint $table) {
            $table->increments('id_tipo_etapa_pa');
            $table->string('nombre', 255)->unique();
        });

        // 2. Tabla EstadoEtapaCronogramaPeriodoAcademico
        Schema::create('estado_etapa_cronograma_periodo_academico', function (Blueprint $table) {
            $table->increments('id_estado_etapa_pa');
            $table->string('nombre', 255)->unique();
        });

        // 3. Tabla CronogramaPeriodoAcademico
        Schema::create('cronograma_periodo_academico', function (Blueprint $table) {
            $table->unsignedInteger('id_periodo_academico');
            $table->unsignedInteger('id_tipo_etapa_pa');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->unsignedInteger('id_estado_etapa_pa')->default(EstadoEtapaCronogramaPeriodoAcademico::ACTIVO);

            $table->foreign('id_periodo_academico')
                ->references('id_periodo_academico')
                ->on('periodos_academicos')
                ->onDelete('cascade');

            $table->foreign('id_tipo_etapa_pa')
                ->references('id_tipo_etapa_pa')
                ->on('tipo_etapa_cronograma_periodo_academico');

            $table->foreign('id_estado_etapa_pa')
                ->references('id_estado_etapa_pa')
                ->on('estado_etapa_cronograma_periodo_academico');

            $table->primary(['id_periodo_academico', 'id_tipo_etapa_pa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronograma_periodo_academico');
        Schema::dropIfExists('estado_etapa_cronograma_periodo_academico');
        Schema::dropIfExists('tipo_etapa_cronograma_periodo_academico');
    }
};
