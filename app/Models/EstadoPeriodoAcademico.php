<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPeriodoAcademico extends Model
{
    const PROGRAMADO = 1;
    const INACTIVO = 2;
    const FINALIZADO = 3;
    const ANULADO = 4;

    protected $table = 'estado_periodo_academico';
    protected $primaryKey = 'id_estado_periodo_academico';
    public $timestamps = false;

    protected $fillable = ['id_estado_periodo_academico', 'nombre'];


    public function esInactivo()
    {
        return $this->getKey() == self::INACTIVO;
    }

    public function esFinalizado()
    {
        return $this->getKey() == self::FINALIZADO;
    }

    public function esProgramado()
    {
        return $this->getKey() == self::PROGRAMADO;
    }

    public function esAnulado()
    {
        return $this->getKey() == self::ANULADO;
    }

    public static function inactivo()
    {
        return EstadoPeriodoAcademico::find(self::INACTIVO)->first();
    }

    public static function finalizado()
    {
        return EstadoPeriodoAcademico::find(self::FINALIZADO)->first();
    }

    public static function programado()
    {
        return EstadoPeriodoAcademico::find(self::PROGRAMADO)->first();
    }

    public static function anulado()
    {
        return EstadoPeriodoAcademico::find(self::ANULADO)->first();
    }
}