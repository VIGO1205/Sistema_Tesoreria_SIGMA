<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * sexo?
     */
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->increments('id_alumno');
            $table->string('codigo_educando',20)->nullable();
            $table->string('codigo_modular', 20)->nullable();
            $table->smallInteger('año_ingreso')->nullable();
            $table->string('dni', 8)->unique();
            $table->string('apellido_paterno', 50);
            $table->string('apellido_materno', 50)->nullable();
            $table->string('primer_nombre', 50);
            $table->text('otros_nombres')->nullable();
            $table->char('sexo', 1); 
            $table->date('fecha_nacimiento');
            $table->string('pais', 20)->nullable();
            $table->string('departamento', 40)->nullable();
            $table->string('provincia', 40)->nullable();
            $table->string('distrito', 40)->nullable();
            $table->string('lengua_materna', 50)->nullable();
            $table->char('estado_civil', 1)->nullable();
            $table->string('religion', 50)->nullable();
            $table->date('fecha_bautizo')->nullable();
            $table->string('parroquia_bautizo', 100)->nullable();
            $table->string('colegio_procedencia', 100)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('medio_transporte', 50)->nullable();
            $table->string('tiempo_demora', 20)->nullable();
            $table->string('material_vivienda', 100)->nullable();
            $table->string('energia_electrica', 100)->nullable();
            $table->string('agua_potable', 100)->nullable();
            $table->string('desague', 100)->nullable();
            $table->string('ss_hh', 100)->nullable();
            $table->integer('num_habitaciones')->nullable();
            $table->integer('num_habitantes')->nullable();
            $table->string('situacion_vivienda', 100)->nullable();
            $table->char('escala', 2)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
