<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use App\Models\PeriodoAcademico;
use Illuminate\Database\Seeder;

class PeriodoAcademicoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear períodos académicos
        $periodos = [
            ['nombre' => '2024'],
            ['nombre' => '2025'],
            ['nombre' => '2026'],
        ];

        foreach ($periodos as $periodo) {
            PeriodoAcademico::firstOrCreate(
                ['nombre' => $periodo['nombre']],
                ['estado' => true]
            );
        }

        // Establecer 2026 como período actual
        $periodo2026 = PeriodoAcademico::where('nombre', '2026')->first();

        if ($periodo2026) {
            Configuracion::establecer('ID_PERIODO_ACADEMICO_ACTUAL', $periodo2026->id_periodo_academico);
        }
    }
}