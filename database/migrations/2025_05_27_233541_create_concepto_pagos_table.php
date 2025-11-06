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
        Schema::create('conceptos_pago', function (Blueprint $table) {
            $table->increments('id_concepto');

            $table->string('descripcion', 100);

            $table->string('escala', 2)->nullable();

            $table->decimal('monto', 10, 2);

            $table->boolean("estado")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conceptos_pago');
    }
};
