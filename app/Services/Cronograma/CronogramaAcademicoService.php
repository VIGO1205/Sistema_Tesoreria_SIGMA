<?php

namespace App\Services\Cronograma;

use App\Interfaces\ICronogramaAcademicoService;
use App\Models\Configuracion;
use App\Models\EstadoPeriodoAcademico;
use App\Models\PeriodoAcademico;

class CronogramaAcademicoService implements ICronogramaAcademicoService
{
    protected static $periodoAcademico;

    public static function establecerPeriodo(PeriodoAcademico $periodoAcademico)
    {
        self::$periodoAcademico = $periodoAcademico;
    }

    public static function obtenerPeriodoPorID(int $id): PeriodoAcademico
    {
        return PeriodoAcademico::findOrFail($id);
    }

    public static function esActual(PeriodoAcademico $periodoAcademico)
    {
        return self::$periodoAcademico->getKey() == $periodoAcademico->getKey();
    }

    public static function obtenerTodosLosPeriodos()
    {
        return PeriodoAcademico::where('id_estado_periodo_academico', '<>', EstadoPeriodoAcademico::ANULADO)->get();
    }

    public static function periodoActual(): PeriodoAcademico|null
    {
        return self::$periodoAcademico;
    }

    public static function establecerPeriodoActual(PeriodoAcademico $periodoAcademico): void
    {
        if ($periodoAcademico->estado->esAnulado()) {
            throw new \Exception('El periodo académico actual no puede estar anulado');
        }

        self::$periodoAcademico = $periodoAcademico;
        Configuracion::establecer(Configuracion::ID_PERIODO_ACADEMICO_ACTUAL, $periodoAcademico->getKey());
    }

    public static function preMatriculaHabilitada(): bool
    {
        return self::$periodoAcademico->estado->esProgramado() && self::$periodoAcademico->estaEnPrematricula();
    }

    public static function matriculaHabilitada(): bool
    {
        return self::$periodoAcademico->estado->esProgramado()
            && (self::$periodoAcademico->estaEnMatricula() || self::$periodoAcademico->estaEnMatriculaExtemporanea());
    }

    public static function estaEnCurso(): bool
    {
        return self::$periodoAcademico->estado->esProgramado() && self::$periodoAcademico->estaEnEjercicio();
    }

    public static function matriculaExtemporaneaHabilitada(): bool
    {
        return self::$periodoAcademico->estado->esProgramado() && self::$periodoAcademico->estaEnMatriculaExtemporanea();
    }
}