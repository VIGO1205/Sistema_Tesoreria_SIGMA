<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_grado';

    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_nivel',
        'nombre_grado',
        'estado',
    ];

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'cursos_grados', 'id_grado', 'id_curso')
        ->withPivot('id_curso_grado', 'año_escolar')
        ->wherePivot('estado', 1);
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'id_grado', 'id_grado');
    }

    public function nivelEducativo()
    {
        return $this->belongsTo(NivelEducativo::class, 'id_nivel');
    }


}
