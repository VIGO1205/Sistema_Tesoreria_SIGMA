<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudPrematricula extends Model
{
    protected $table = 'solicitudes_prematricula';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        // Apoderado
        'dni_apoderado',
        'apellido_paterno_apoderado',
        'apellido_materno_apoderado',
        'primer_nombre_apoderado',
        'otros_nombres_apoderado',
        'numero_contacto',
        'correo_electronico',
        'direccion_apoderado',
        'parentesco',
        // Alumno
        'dni_alumno',
        'apellido_paterno_alumno',
        'apellido_materno_alumno',
        'primer_nombre_alumno',
        'otros_nombres_alumno',
        'sexo',
        'fecha_nacimiento',
        'direccion_alumno',
        'telefono_alumno',
        'colegio_procedencia',
        'id_grado',
        // Documentos
        'partida_nacimiento',
        'certificado_estudios',
        'foto_alumno',
        // Estado
        'estado',
        'observaciones',
        'motivo_rechazo',
        'id_usuario',
        'revisado_por',
        'fecha_revision',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_revision' => 'datetime',
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function getNombreCompletoApoderadoAttribute()
    {
        return trim("{$this->primer_nombre_apoderado} {$this->otros_nombres_apoderado} {$this->apellido_paterno_apoderado} {$this->apellido_materno_apoderado}");
    }

    public function getNombreCompletoAlumnoAttribute()
    {
        return trim("{$this->primer_nombre_alumno} {$this->otros_nombres_alumno} {$this->apellido_paterno_alumno} {$this->apellido_materno_alumno}");
    }

    public function getEstadoBadgeAttribute()
    {
        return match($this->estado) {
            'pendiente' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Pendiente</span>',
            'en_revision' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">En Revisión</span>',
            'aprobada' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Aprobada</span>',
            'rechazada' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Rechazada</span>',
            default => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Desconocido</span>',
        };
    }
}