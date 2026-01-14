<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEtapaPeriodoAcademico extends Model
{

    const PREMATRICULA = 1;
    const MATRICULA = 2;
    const EN_EJERCICIO = 3;
    const MATRICULA_EXTEMPORANEA = 4;

    protected $table = 'tipo_etapa_cronograma_periodo_academico';
    protected $primaryKey = 'id_tipo_etapa_pa';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function esPrematricula()
    {
        return $this->getKey() == self::PREMATRICULA;
    }

    public function esMatricula()
    {
        return $this->getKey() == self::MATRICULA;
    }

    public function esEnEjercicio()
    {
        return $this->getKey() == self::EN_EJERCICIO;
    }

    public function esMatriculaExtemporanea()
    {
        return $this->getKey() == self::MATRICULA_EXTEMPORANEA;
    }

    public static function prematricula()
    {
        return TipoEtapaPeriodoAcademico::find(self::PREMATRICULA);
    }

    public static function matricula()
    {
        return TipoEtapaPeriodoAcademico::find(self::MATRICULA);
    }

    public static function enEjercicio()
    {
        return TipoEtapaPeriodoAcademico::find(self::EN_EJERCICIO);
    }

    public static function matriculaExtemporanea()
    {
        return TipoEtapaPeriodoAcademico::find(self::MATRICULA_EXTEMPORANEA);
    }
}
