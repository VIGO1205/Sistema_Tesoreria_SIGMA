<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('familiares', function (Blueprint $table) {
            $table->increments('idFamiliar');
            $table->string('dni', 8)->unique();
            $table->string('apellido_paterno', 45);
            $table->string('apellido_materno', 45)->nullable();
            $table->string('primer_nombre', 45);
            $table->text('otros_nombres')->nullable();
            $table->string('numero_contacto', 45)->nullable();
            $table->string('correo_electronico', 45)->nullable();
            $table->foreignId('id_usuario')->nullable()->constrained('users', 'id_usuario')->onDelete('cascade');
            $table->boolean("estado")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('familiares');
    }
};
