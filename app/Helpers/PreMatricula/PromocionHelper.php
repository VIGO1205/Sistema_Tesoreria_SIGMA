<?php

namespace App\Helpers\PreMatricula;

use App\Models\Grado;
use App\Models\NivelEducativo;
use App\Models\Matricula;
use Carbon\Carbon;

class PromocionHelper
{
    /**
     * Configuración del período de prematrícula
     */
    const PREMATRICULA_MES_INICIO = 11;  // Noviembre
    const PREMATRICULA_DIA_INICIO = 1;
    const PREMATRICULA_MES_FIN = 2;      // Febrero
    const PREMATRICULA_DIA_FIN = 28;

    /**
     * Obtiene el siguiente grado para un alumno basado en su última matrícula
     */
    public static function obtenerSiguienteGrado($ultimaMatricula)
    {
        if (!$ultimaMatricula || !$ultimaMatricula->grado) {
            return null;
        }

        $gradoActual = $ultimaMatricula->grado;

        // 1️⃣ Buscar siguiente grado dentro del mismo nivel
        $siguienteGrado = Grado::where('id_nivel', $gradoActual->id_nivel)
            ->where('id_grado', '>', $gradoActual->id_grado)
            ->where('estado', 1)
            ->orderBy('id_grado', 'asc')
            ->first();

        // 2️⃣ Si no existe, pasar al primer grado del siguiente nivel
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


    /**
     * Verifica si el período de prematrícula está activo
     */
    public static function periodoPrematriculaActivo()
    {
        $hoy = Carbon::now();
        $año = $hoy->year;

        // Período: Nov-Dic del año actual hasta Feb del año siguiente
        $inicioNov = Carbon::create($año, self::PREMATRICULA_MES_INICIO, self::PREMATRICULA_DIA_INICIO);
        $finFeb = Carbon::create($año + 1, self::PREMATRICULA_MES_FIN, self::PREMATRICULA_DIA_FIN);

        // Verificar si estamos en Ene-Feb del año actual (período que inició el año pasado)
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

    /**
     * Verifica si el alumno ya tiene matrícula o prematrícula para un año específico
     */
    public static function tieneMatriculaEnAño($idAlumno, $añoEscolar)
    {
        return Matricula::where('id_alumno', $idAlumno)
            ->where('año_escolar', $añoEscolar)
            ->where('estado', 1)
            ->exists();
    }

    /**
     * Obtiene la última matrícula del alumno
     */
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

        $puedePrematricular = $periodo['activo'] && $siguienteGrado && !$tieneMatricula;

        return [
            'ultima_matricula' => $ultimaMatricula,
            'siguiente_grado' => $siguienteGrado,
            'periodo' => $periodo,
            'tiene_matricula' => $tieneMatricula,
            'puede_prematricular' => $puedePrematricular,
            'mensaje_error' => self::obtenerMensajeError($periodo, $siguienteGrado, $tieneMatricula),
        ];
    }

    /**
     * Obtiene mensaje de error si no puede prematricular
     */
    private static function obtenerMensajeError($periodo, $siguienteGrado, $tieneMatricula)
    {
        if ($tieneMatricula) {
            return 'El alumno ya tiene una matrícula o prematrícula registrada para el año escolar ' . $periodo['año_escolar'] . '.';
        }

        if (!$periodo['activo']) {
            return 'El período de prematrícula no está activo. El período es de Noviembre a Febrero.';
        }

        if (!$siguienteGrado) {
            return 'El alumno ha completado todos los niveles educativos disponibles o no tiene matrícula previa.';
        }

        return null;
    }
}