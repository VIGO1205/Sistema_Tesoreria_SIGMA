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
        Schema::table('pagos', function (Blueprint $table) {
            // Método de pago utilizado
            $table->enum('metodo_pago', ['yape', 'plin', 'transferencia', 'tarjeta', 'paypal', 'efectivo'])
                  ->nullable()
                  ->after('tipo_pago')
                  ->comment('Método de pago utilizado en la pasarela');
            
            // Número de operación generado
            $table->string('numero_operacion', 20)
                  ->nullable()
                  ->after('metodo_pago')
                  ->comment('Número de operación de la transacción');
            
            // Datos adicionales del método de pago (JSON)
            $table->json('datos_adicionales')
                  ->nullable()
                  ->after('numero_operacion')
                  ->comment('Datos adicionales del método de pago (celular, banco, tarjeta, etc.)');
            
            // Ruta del voucher (si se guarda)
            $table->string('voucher_path', 255)
                  ->nullable()
                  ->after('datos_adicionales')
                  ->comment('Ruta del archivo voucher generado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn(['metodo_pago', 'numero_operacion', 'datos_adicionales', 'voucher_path']);
        });
    }
};
