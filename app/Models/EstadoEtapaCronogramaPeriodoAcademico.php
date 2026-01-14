<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoEtapaCronogramaPeriodoAcademico extends Model
{

    const ACTIVO = 1;
    const SUSPENDIDO = 2;
    const ANULADO = 3;
    const FINALIZADO = 4;

    protected $table = 'estado_etapa_cronograma_periodo_academico';
    protected $primaryKey = 'id_estado_etapa_pa';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function esActivo()
    {
        return $this->getKey() == self::ACTIVO;
    }

    public function esSuspendido()
    {
        return $this->getKey() == self::SUSPENDIDO;
    }

    public function esAnulado()
    {
        return $this->getKey() == self::ANULADO;
    }

    public function esFinalizado()
    {
        return $this->getKey() == self::FINALIZADO;
    }

    public static function activo()
    {
        return EstadoEtapaCronogramaPeriodoAcademico::find(self::ACTIVO);
    }

    public static function suspendido()
    {
        return EstadoEtapaCronogramaPeriodoAcademico::find(self::SUSPENDIDO);
    }

    public static function anulado()
    {
        return EstadoEtapaCronogramaPeriodoAcademico::find(self::ANULADO);
    }

    public static function finalizado()
    {
        return EstadoEtapaCronogramaPeriodoAcademico::find(self::FINALIZADO);
    }
}
