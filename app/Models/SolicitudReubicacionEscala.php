<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudReubicacionEscala extends Model
{
    protected $table = 'solicitudes_reubicacion_escala';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'id_alumno',
        'escala_actual',
        'escala_solicitada',
        'justificacion',
        'archivo_sisfoh',
        'estado',
        'observacion_admin',
        'fecha_revision',
    ];

    protected $casts = [
        'fecha_revision' => 'datetime',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }
}