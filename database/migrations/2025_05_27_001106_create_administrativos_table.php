<?php

use App\Models\User;
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
        Schema::create('administrativos', function (Blueprint $table) {
            $table->id("id_administrativo");
            $table->foreignIdFor(User::class, "id_usuario")->constrained()->cascadeOnDelete();
            $table->string('apellido_paterno', 50);
            $table->string('apellido_materno', 50)->nullable();
            $table->string('primer_nombre', 50);
            $table->text('otros_nombres')->nullable();
            $table->string('dni', 8);
            $table->string('direccion', 80);
            $table->char('estado_civil', 1);
            $table->string('telefono', 20);
            $table->string('seguro_social', 20);
            $table->date('fecha_ingreso');
            $table->string('cargo');
            $table->integer('sueldo');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrativos');
    }
};
