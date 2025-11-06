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
        Schema::create('catedras', function (Blueprint $table) {
            $table->increments('id_catedra');
            $table->unsignedInteger('id_personal');
            $table->unsignedInteger('id_curso');
            $table->year('año_escolar');
            $table->unsignedInteger('id_grado');
            $table->char('secciones_nombreSeccion', 2);

            $table->foreign('id_personal')
                  ->references('id_personal')
                  ->on('personal')
                  ->onDelete('cascade');

            $table->foreign('id_curso')
                  ->references('id_curso')
                  ->on('cursos')
                  ->onDelete('cascade'); 

            $table->foreign(['id_grado', 'secciones_nombreSeccion'])
                  ->references(['id_grado', 'nombreSeccion']) 
                  ->on('secciones')                            
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
        Schema::dropIfExists('catedras');
    }
};
