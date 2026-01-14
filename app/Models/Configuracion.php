<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{

    const ID_PERIODO_ACADEMICO_ACTUAL = 'ID_PERIODO_ACADEMICO_ACTUAL';

    protected $table = 'configuracion';
    protected $primaryKey = 'clave';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'clave',
        'valor',
    ];

    /**
     * Obtener un valor de configuración
     */
    public static function obtener(string $clave, $default = null)
    {
        $config = static::find($clave);
        return $config ? $config->valor : $default;
    }

    /**
     * Establecer un valor de configuración
     */
    public static function establecer(string $clave, $valor)
    {
        return static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor]
        );
    }
}