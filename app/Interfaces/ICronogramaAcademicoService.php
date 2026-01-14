<?php

namespace App\Interfaces;

use App\Models\PeriodoAcademico;

interface ICronogramaAcademicoService
{
    public static function esActual(PeriodoAcademico $periodoAcademico);
    public static function obtenerPeriodoPorID(int $id): PeriodoAcademico;
    public static function obtenerTodosLosPeriodos();
    public static function periodoActual(): PeriodoAcademico|null;
    public static function establecerPeriodoActual(PeriodoAcademico $periodoAcademico): void;
    public static function preMatriculaHabilitada(): bool;
    public static function matriculaHabilitada(): bool;
    public static function estaEnCurso(): bool;
    public static function matriculaExtemporaneaHabilitada(): bool;
}
