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
        Schema::create('personal', function (Blueprint $table) {
            $table->increments('id_personal');
            $table->foreignId('id_usuario')
                  ->constrained('users', 'id_usuario') 
                  ->onDelete('cascade');
            $table->string('codigo_personal', 10)->nullable();
            $table->string('apellido_paterno', 50);
            $table->string('apellido_materno', 50)->nullable();
            $table->string('primer_nombre', 50);
            $table->text('otros_nombres')->nullable();
            $table->string('dni', 20)->unique();
            $table->string('direccion', 255)->nullable();
            $table->char('estado_civil', 1)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('seguro_social', 50)->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->string('departamento', 50)->nullable();
            $table->string('categoria', 50)->nullable();
            $table->unsignedInteger('id_departamento')->nullable();
            $table->foreign('id_departamento')
                  ->references('id_departamento')
                  ->on('departamentos_academicos')
                  ->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }

    
};
