<?php

namespace Database\Seeders;

use App\Models\EstadoEtapaCronogramaPeriodoAcademico;
use App\Models\TipoEtapaPeriodoAcademico;
use Illuminate\Database\Seeder;

class CronogramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id_tipo_etapa_pa' => TipoEtapaPeriodoAcademico::PREMATRICULA, 'nombre' => 'PRE-MATRICULA'],
            ['id_tipo_etapa_pa' => TipoEtapaPeriodoAcademico::MATRICULA, 'nombre' => 'MATRICULA'],
            ['id_tipo_etapa_pa' => TipoEtapaPeriodoAcademico::EN_EJERCICIO, 'nombre' => 'EN EJERCICIO'],
            ['id_tipo_etapa_pa' => TipoEtapaPeriodoAcademico::MATRICULA_EXTEMPORANEA, 'nombre' => 'MATRICULA EXTEMPORANEA'],
        ];

        TipoEtapaPeriodoAcademico::insertOrIgnore($tipos);

        $estados = [
            ['id_estado_etapa_pa' => EstadoEtapaCronogramaPeriodoAcademico::ACTIVO, 'nombre' => 'ACTIVO'],
            ['id_estado_etapa_pa' => EstadoEtapaCronogramaPeriodoAcademico::SUSPENDIDO, 'nombre' => 'SUSPENDIDO'],
            ['id_estado_etapa_pa' => EstadoEtapaCronogramaPeriodoAcademico::ANULADO, 'nombre' => 'ANULADO'],
            ['id_estado_etapa_pa' => EstadoEtapaCronogramaPeriodoAcademico::FINALIZADO, 'nombre' => 'FINALIZADO'],
        ];

        EstadoEtapaCronogramaPeriodoAcademico::insertOrIgnore($estados);
    }
}
