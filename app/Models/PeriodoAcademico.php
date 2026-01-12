<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoAcademico extends Model
{
    protected $table = 'periodos_academicos';
    protected $primaryKey = 'id_periodo_academico';

    protected $fillable = [
        'nombre',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_periodo_academico', 'id_periodo_academico');
    }

    /**
     * Obtener el período académico actual
     */
    public static function actual()
    {
        $config = Configuracion::where('clave', 'ID_PERIODO_ACADEMICO_ACTUAL')->first();
        
        if ($config && $config->valor) {
            return static::find($config->valor);
        }
        
        return null;
    }

    /**
     * Establecer este período como actual
     */
    public function establecerComoActual()
    {
        Configuracion::updateOrCreate(
            ['clave' => 'ID_PERIODO_ACADEMICO_ACTUAL'],
            ['valor' => $this->id_periodo_academico]
        );
    }
}