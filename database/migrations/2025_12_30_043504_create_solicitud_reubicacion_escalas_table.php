<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_reubicacion_escala', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->unsignedInteger('id_alumno');
            $table->char('escala_actual', 1);
            $table->char('escala_solicitada', 1);
            $table->text('justificacion');
            $table->string('archivo_sisfoh');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('observacion_admin')->nullable();
            $table->timestamp('fecha_revision')->nullable();
            $table->timestamps();

            $table->foreign('id_alumno')->references('id_alumno')->on('alumnos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_reubicacion_escala');
    }
};