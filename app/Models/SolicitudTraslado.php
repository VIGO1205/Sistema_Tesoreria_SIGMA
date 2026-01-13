<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTraslado extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_traslado';

    protected $primaryKey = 'id_solicitud';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'codigo_solicitud',
        'id_alumno',
        'colegio_destino',
        'motivo_traslado',
        'fecha_traslado',
        'direccion_nuevo_colegio',
        'telefono_nuevo_colegio',
        'observaciones',
        'estado',
        'fecha_solicitud',
        'tipo_solicitud',
    ];

    protected function casts(): array
    {
        return [
            'fecha_traslado' => 'date',
            'fecha_solicitud' => 'datetime',
        ];
    }

    /**
     * Relación con el modelo Alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }
}
