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
        Schema::create('distribucion_pago_deuda', function (Blueprint $table) {
            $table->increments('id_distribucion');
            $table->unsignedInteger('id_pago');
            $table->unsignedInteger('id_deuda');
            $table->decimal('monto_aplicado', 10, 2);
            $table->timestamps();
            
            // Llaves foráneas
            $table->foreign('id_pago')->references('id_pago')->on('pagos')->onDelete('cascade');
            $table->foreign('id_deuda')->references('id_deuda')->on('deudas')->onDelete('cascade');
            
            // Índices para mejorar consultas
            $table->index('id_pago');
            $table->index('id_deuda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribucion_pago_deuda');
    }
};
