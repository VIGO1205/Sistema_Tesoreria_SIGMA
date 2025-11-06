<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PoliticaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Políticas de MORA para mes actual
        DB::table('politica')->insert([
            [
                'id_concepto' => null, // Aplica a todos los conceptos
                'nombre' => 'Mora 5% - Días 08 al 12',
                'tipo' => 'mora',
                'porcentaje' => 5.00,
                'dias_minimo' => 8,
                'dias_maximo' => 12,
                'condiciones' => 'Aplica del día 08 al 12 de cada mes para deudas del mes actual',
                'estado' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_concepto' => null,
                'nombre' => 'Mora 8% - Días 13 al 17',
                'tipo' => 'mora',
                'porcentaje' => 8.00,
                'dias_minimo' => 13,
                'dias_maximo' => 17,
                'condiciones' => 'Aplica del día 13 al 17 de cada mes para deudas del mes actual',
                'estado' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_concepto' => null,
                'nombre' => 'Mora 10% - Día 18 en adelante',
                'tipo' => 'mora',
                'porcentaje' => 10.00,
                'dias_minimo' => 18,
                'dias_maximo' => 31,
                'condiciones' => 'Aplica del día 18 hasta fin de mes para deudas del mes actual',
                'estado' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_concepto' => null,
                'nombre' => 'Mora Meses Anteriores - 15% por mes',
                'tipo' => 'mora',
                'porcentaje' => 15.00,
                'dias_minimo' => null,
                'dias_maximo' => null,
                'condiciones' => 'Aplica 15% fijo por cada mes anterior no pagado',
                'estado' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Política de DESCUENTO por adelanto
        DB::table('politica')->insert([
            [
                'id_concepto' => null,
                'nombre' => 'Descuento Adelanto - 10%',
                'tipo' => 'descuento',
                'porcentaje' => 10.00,
                'dias_minimo' => null,
                'dias_maximo' => null,
                'condiciones' => 'Aplica 10% de descuento por cada mes adelantado. Solo se puede adelantar si pagó la deuda actual.',
                'estado' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
