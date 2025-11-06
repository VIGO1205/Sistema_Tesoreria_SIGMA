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
        Schema::create('registro_historico', function (Blueprint $table) {
            $table->increments('id_registro_historico');

            // id_concepto_accion INT FK
            $table->unsignedInteger('id_concepto_accion');

            $table->foreignId('id_autor')
                ->constrained('users', 'id_usuario') // 'users' es el nombre de la tabla, 'id_usuario' es la PK.
                ->onDelete('cascade'); // Si el usuario autor se elimina, se borran sus registros.
            
            $table->unsignedBigInteger('id_entidad_afectada')->nullable();
            $table->string('tipo_entidad_afectada')->nullable();

            // fecha_accion DATETIME
            $table->dateTime('fecha_accion');

            // observacion TEXT
            $table->text('observacion')->nullable();

            // estado TINYINT
            $table->boolean('estado')->default(true);

            $table->index(['id_entidad_afectada', 'tipo_entidad_afectada'], 'hist_ent_afect_idx');
            $table->index('fecha_accion');

            $table->foreign('id_concepto_accion') 
                  ->references('id_concepto_accion') 
                  ->on('concepto_accion') 
                  ->onDelete('restrict'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_historico');
    }
};
