<?php

namespace Database\Seeders;

use App\Interfaces\ICronogramaAcademicoService;
use App\Models\Configuracion;
use App\Models\EstadoPeriodoAcademico;
use App\Models\PeriodoAcademico;
use App\Services\Cronograma\CronogramaAcademicoService;
use Illuminate\Database\Seeder;

class PeriodoAcademicoSeeder extends Seeder
{
    protected function establecerPeriodoAcademicoActual(PeriodoAcademico $periodo)
    {
        Configuracion::establecer(Configuracion::ID_PERIODO_ACADEMICO_ACTUAL, $periodo->id_periodo_academico);
    }
    public function run(): void
    {
        $estados = [
            ['id_estado_periodo_academico' => EstadoPeriodoAcademico::PROGRAMADO, 'nombre' => 'PROGRAMADO'],
            ['id_estado_periodo_academico' => EstadoPeriodoAcademico::INACTIVO, 'nombre' => 'INACTIVO'],
            ['id_estado_periodo_academico' => EstadoPeriodoAcademico::FINALIZADO, 'nombre' => 'FINALIZADO'],
            ['id_estado_periodo_academico' => EstadoPeriodoAcademico::ANULADO, 'nombre' => 'ANULADO'],
        ];

        EstadoPeriodoAcademico::insert($estados);

        // Crear períodos académicos
        $periodos = [
            ['nombre' => '2024', 'id_estado_periodo_academico' => EstadoPeriodoAcademico::PROGRAMADO],
            ['nombre' => '2025', 'id_estado_periodo_academico' => EstadoPeriodoAcademico::PROGRAMADO],
            ['nombre' => '2026', 'id_estado_periodo_academico' => EstadoPeriodoAcademico::PROGRAMADO],
        ];

        PeriodoAcademico::insert($periodos);

        // Establecer 2026 como período actual
        $this->establecerPeriodoAcademicoActual(PeriodoAcademico::where('nombre', '2026')->first());
    }
}