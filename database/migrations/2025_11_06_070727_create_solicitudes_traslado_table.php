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
        Schema::create('solicitudes_traslado', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->string('codigo_solicitud', 50)->unique()->comment('Código único de la solicitud');
            $table->unsignedInteger('id_alumno')->comment('ID del alumno que solicita el traslado');
            $table->string('colegio_destino', 255)->comment('Nombre del colegio de destino');
            $table->text('motivo_traslado')->comment('Motivo del traslado');
            $table->date('fecha_traslado')->comment('Fecha prevista del traslado');
            $table->string('direccion_nuevo_colegio', 255)->nullable()->comment('Dirección del nuevo colegio');
            $table->string('telefono_nuevo_colegio', 20)->nullable()->comment('Teléfono del nuevo colegio');
            $table->text('observaciones')->nullable()->comment('Observaciones adicionales');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'completado'])->default('pendiente')->comment('Estado de la solicitud');
            $table->datetime('fecha_solicitud')->comment('Fecha y hora de la solicitud');
            $table->timestamps();

            // Foreign key
            $table->foreign('id_alumno')->references('id_alumno')->on('alumnos')->onDelete('cascade');

            // Índices
            $table->index('id_alumno');
            $table->index('codigo_solicitud');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_traslado');
    }
};
