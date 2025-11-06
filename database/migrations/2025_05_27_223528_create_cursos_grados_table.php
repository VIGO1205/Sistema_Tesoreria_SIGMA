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
        Schema::create('cursos_grados', function (Blueprint $table) {
            $table->increments('id_curso_grado');
            $table->unsignedInteger('id_curso');
            $table->unsignedInteger('id_grado');
            $table->year('año_escolar');
            $table->foreign('id_curso')
                  ->references('id_curso')
                  ->on('cursos')
                  ->onDelete('cascade'); 
            $table->foreign('id_grado')
                  ->references('id_grado')
                  ->on('grados')
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
        Schema::dropIfExists('cursos_grados');
    }
};
