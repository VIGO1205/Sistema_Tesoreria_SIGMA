<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deudas', function (Blueprint $table) {
            $table->increments('id_deuda');

            $table->unsignedInteger('id_alumno');

            $table->unsignedInteger('id_concepto');

            $table->date('fecha_limite')->nullable(); 

            $table->decimal('monto_total', 10, 2)->default(0);

            $table->string('periodo', 25)->nullable();

            $table->decimal('monto_a_cuenta', 10, 2)->default(0);
            $table->decimal('monto_adelantado', 10, 2)->default(0);

            $table->text('observacion')->nullable();

            $table->foreign('id_alumno')
                  ->references('id_alumno')
                  ->on('alumnos')
                  ->onDelete('cascade'); 

            $table->foreign('id_concepto')
                  ->references('id_concepto')
                  ->on('conceptos_pago')
                  ->onDelete('cascade');

            $table->boolean("estado")->default(true);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('deudas');
    }
};
