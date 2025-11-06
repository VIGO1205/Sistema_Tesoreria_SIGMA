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
        Schema::create('composiciones_familiares', function (Blueprint $table) {
            $table->unsignedInteger('id_alumno');
            $table->unsignedInteger('id_familiar');
            $table->primary(['id_alumno', 'id_familiar']);
            $table->string('parentesco');

            $table->foreign('id_alumno')
                ->references('id_alumno') 
                ->on('alumnos')
                ->onDelete('cascade');
            $table->foreign('id_familiar')
                ->references('idFamiliar') 
                ->on('familiares')
                ->onDelete('cascade');

            $table->boolean("estado")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composiciones_familiares');
    }
};
