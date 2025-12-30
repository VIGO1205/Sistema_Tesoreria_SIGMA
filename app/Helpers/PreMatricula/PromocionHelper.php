<?php

namespace App\Helpers\PreMatricula;

use App\Models\Grado;
use App\Models\NivelEducativo;
use App\Models\Matricula;
use Carbon\Carbon;

class PromocionHelper
{
    const PREMATRICULA_MES_INICIO = 11;
    const PREMATRICULA_DIA_INICIO = 1;
    const PREMATRICULA_MES_FIN = 2;
    const PREMATRICULA_DIA_FIN = 28;

    public static function obtenerSiguienteGrado($ultimaMatricula)
    {
        if (!$ultimaMatricula || !$ultimaMatricula->grado) {
            return null;
        }

        $gradoActual = $ultimaMatricula->grado;

        $siguienteGrado = Grado::where('id_nivel', $gradoActual->id_nivel)
            ->where('id_grado', '>', $gradoActual->id_grado)
            ->where('estado', 1)
            ->orderBy('id_grado', 'asc')
            ->first();

        if (!$siguienteGrado) {
            $siguienteNivel = NivelEducativo::where('id_nivel', '>', $gradoActual->id_nivel)
                ->where('estado', 1)
                ->orderBy('id_nivel', 'asc')
                ->first();

            if ($siguienteNivel) {
                $siguienteGrado = Grado::where('id_nivel', $siguienteNivel->id_nivel)
                    ->where('estado', 1)
                    ->orderBy('id_grado', 'asc')
                    ->first();
            }
        }

        return $siguienteGrado;
    }

    public static function periodoPrematriculaActivo()
    {
        $hoy = Carbon::now();
        $año = $hoy->year;

        $inicioNov = Carbon::create($año, self::PREMATRICULA_MES_INICIO, self::PREMATRICULA_DIA_INICIO);
        $finFeb = Carbon::create($año + 1, self::PREMATRICULA_MES_FIN, self::PREMATRICULA_DIA_FIN);

        $inicioNovAnterior = Carbon::create($año - 1, self::PREMATRICULA_MES_INICIO, self::PREMATRICULA_DIA_INICIO);
        $finFebActual = Carbon::create($año, self::PREMATRICULA_MES_FIN, self::PREMATRICULA_DIA_FIN);

        if ($hoy->between($inicioNov, $finFeb)) {
            return [
                'activo' => true,
                'año_escolar' => $año + 1,
                'fecha_inicio' => $inicioNov,
                'fecha_fin' => $finFeb,
            ];
        }

        if ($hoy->between($inicioNovAnterior, $finFebActual)) {
            return [
                'activo' => true,
                'año_escolar' => $año,
                'fecha_inicio' => $inicioNovAnterior,
                'fecha_fin' => $finFebActual,
            ];
        }

        return [
            'activo' => false,
            'año_escolar' => null,
            'fecha_inicio' => null,
            'fecha_fin' => null,
        ];
    }

    public static function tieneMatriculaEnAño($idAlumno, $añoEscolar)
    {
        return Matricula::where('id_alumno', $idAlumno)
            ->where('año_escolar', $añoEscolar)
            ->where('estado', 1)
            ->exists();
    }

    public static function obtenerUltimaMatricula($idAlumno)
    {
        return Matricula::where('id_alumno', $idAlumno)
            ->where('estado', 1)
            ->orderBy('año_escolar', 'desc')
            ->orderBy('id_matricula', 'desc')
            ->with(['grado', 'grado.nivelEducativo', 'seccion'])
            ->first();
    }

    /**
     * Obtiene información completa para la prematrícula
     */
    public static function obtenerInfoPrematricula($idAlumno)
    {
        $ultimaMatricula = self::obtenerUltimaMatricula($idAlumno);
        $siguienteGrado = self::obtenerSiguienteGrado($ultimaMatricula);
        $periodo = self::periodoPrematriculaActivo();

        $tieneMatricula = false;
        if ($periodo['activo'] && $periodo['año_escolar']) {
            $tieneMatricula = self::tieneMatriculaEnAño($idAlumno, $periodo['año_escolar']);
        }

        // Detectar si es alumno nuevo (sin matrícula previa)
        $esAlumnoNuevo = ($ultimaMatricula === null);

        // Puede prematricular si:
        // - Período activo Y no tiene matrícula ese año
        // - Si es alumno nuevo: puede (elegirá grado)
        // - Si es alumno existente: debe tener siguiente grado disponible
        $puedePrematricular = $periodo['activo'] && !$tieneMatricula && ($esAlumnoNuevo || $siguienteGrado);

        return [
            'ultima_matricula' => $ultimaMatricula,
            'siguiente_grado' => $siguienteGrado,
            'periodo' => $periodo,
            'tiene_matricula' => $tieneMatricula,
            'puede_prematricular' => $puedePrematricular,
            'es_alumno_nuevo' => $esAlumnoNuevo,
            'mensaje_error' => self::obtenerMensajeError($periodo, $siguienteGrado, $tieneMatricula, $esAlumnoNuevo),
        ];
    }

    private static function obtenerMensajeError($periodo, $siguienteGrado, $tieneMatricula, $esAlumnoNuevo = false)
    {
        if ($tieneMatricula) {
            return 'El alumno ya tiene una matrícula o prematrícula registrada para el año escolar ' . $periodo['año_escolar'] . '.';
        }

        if (!$periodo['activo']) {
            return 'El período de prematrícula no está activo. El período es de Noviembre a Febrero.';
        }

        if (!$esAlumnoNuevo && !$siguienteGrado) {
            return 'El alumno ha completado todos los niveles educativos disponibles.';
        }

        return null;
    }

    /**
     * Obtiene todos los grados disponibles para alumnos nuevos
     */
    public static function obtenerGradosDisponibles()
    {
        return Grado::where('estado', 1)
            ->with('nivelEducativo')
            ->orderBy('id_nivel', 'asc')
            ->orderBy('id_grado', 'asc')
            ->get();
    }
}