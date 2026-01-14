<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoAcademico extends Model
{
    protected $table = 'periodos_academicos';
    protected $primaryKey = 'id_periodo_academico';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'id_estado_periodo_academico',
    ];

    public function estado()
    {
        return $this->belongsTo(EstadoPeriodoAcademico::class, 'id_estado_periodo_academico', 'id_estado_periodo_academico');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_periodo_academico', 'id_periodo_academico');
    }

    public function cronograma()
    {
        return $this->hasMany(CronogramaPeriodoAcademico::class, 'id_periodo_academico', 'id_periodo_academico');
    }

    public function prematricula()
    {
        return CronogramaPeriodoAcademico::obtener($this->getKey(), TipoEtapaPeriodoAcademico::PREMATRICULA);
    }

    public function estaEnPrematricula()
    {
        return $this->prematricula()?->esAplicable() ?? false;
    }

    public function matricula()
    {
        return CronogramaPeriodoAcademico::obtener($this->getKey(), TipoEtapaPeriodoAcademico::MATRICULA);
    }

    public function estaEnMatricula()
    {
        return $this->matricula()?->esAplicable() ?? false;
    }

    public function enEjercicio()
    {
        return CronogramaPeriodoAcademico::obtener($this->getKey(), TipoEtapaPeriodoAcademico::EN_EJERCICIO);
    }

    public function estaEnEjercicio()
    {
        return $this->enEjercicio()?->esAplicable() ?? false;
    }

    public function matriculaExtemporanea()
    {
        return CronogramaPeriodoAcademico::obtener($this->getKey(), TipoEtapaPeriodoAcademico::MATRICULA_EXTEMPORANEA);
    }

    public function estaEnMatriculaExtemporanea()
    {
        return $this->matriculaExtemporanea()?->esAplicable() ?? false;
    }

    public function programar()
    {
        $this->id_estado_periodo_academico = EstadoPeriodoAcademico::PROGRAMADO;
        $this->save();
    }

    public function desactivar()
    {
        $this->id_estado_periodo_academico = EstadoPeriodoAcademico::INACTIVO;
        $this->save();
    }

    public function anular()
    {
        $this->id_estado_periodo_academico = EstadoPeriodoAcademico::ANULADO;
        $this->save();
    }

    public function finalizar()
    {
        $this->id_estado_periodo_academico = EstadoPeriodoAcademico::FINALIZADO;
        $this->save();
    }
}