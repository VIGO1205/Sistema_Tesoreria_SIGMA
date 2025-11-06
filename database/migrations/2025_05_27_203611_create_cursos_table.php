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
        Schema::create('cursos', function (Blueprint $table) {
            $table->increments('id_curso');
            $table->unsignedInteger('id_nivel');
            $table->string('codigo_curso', 10);
            $table->string('nombre_curso', 100);
            $table->foreign('id_nivel')
                  ->references('id_nivel')
                  ->on('niveles_educativos')
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
        Schema::dropIfExists('cursos');
    }
};
