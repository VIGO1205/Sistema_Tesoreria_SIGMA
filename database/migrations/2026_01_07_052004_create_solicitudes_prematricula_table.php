<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_prematricula', function (Blueprint $table) {
            $table->id('id_solicitud');
            
            // ========== DATOS DEL APODERADO (FAMILIAR) ==========
            $table->string('dni_apoderado', 8);
            $table->string('apellido_paterno_apoderado', 50);
            $table->string('apellido_materno_apoderado', 50)->nullable();
            $table->string('primer_nombre_apoderado', 50);
            $table->string('otros_nombres_apoderado', 100)->nullable();
            $table->string('numero_contacto', 20);
            $table->string('correo_electronico', 100)->nullable();
            $table->string('direccion_apoderado', 255)->nullable();
            $table->string('parentesco', 50);
            
            // ========== DATOS DEL ALUMNO ==========
            $table->string('dni_alumno', 8);
            $table->string('apellido_paterno_alumno', 50);
            $table->string('apellido_materno_alumno', 50)->nullable();
            $table->string('primer_nombre_alumno', 50);
            $table->string('otros_nombres_alumno', 100)->nullable();
            $table->char('sexo', 1);
            $table->date('fecha_nacimiento');
            $table->string('direccion_alumno', 255)->nullable();
            $table->string('telefono_alumno', 20)->nullable();
            $table->string('colegio_procedencia', 100)->nullable();
            
            // Grado al que postula
            $table->unsignedInteger('id_grado');
            
            // ========== DOCUMENTOS ==========
            $table->string('partida_nacimiento', 255)->nullable();
            $table->string('certificado_estudios', 255)->nullable();
            $table->string('foto_alumno', 255)->nullable();
            
            // ========== ESTADO Y SEGUIMIENTO ==========
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->text('motivo_rechazo')->nullable();
            
            // Usuario generado
            $table->unsignedBigInteger('id_usuario')->nullable();
            
            // Auditoría
            $table->unsignedBigInteger('revisado_por')->nullable();
            $table->timestamp('fecha_revision')->nullable();
            
            $table->timestamps();
            
            $table->foreign('id_grado')->references('id_grado')->on('grados');
            $table->unique('dni_alumno');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_prematricula');
    }
};