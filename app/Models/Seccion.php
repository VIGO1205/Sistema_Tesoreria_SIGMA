<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones';

    public $incrementing = false;

    protected $fillable = [
        'id_grado',
        'nombreSeccion',
        'estado'
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    public function niveleducativo(){
        return $this->hasOneThrough(
            NivelEducativo::class,
            Grado::class,
            'id_grado',
            'id_nivel',
            'id_grado',
            'id_nivel'
        );
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_grado', 'id_grado')
                    ->where('nombreSeccion', $this->nombreSeccion);
    }

    public function catedras()
    {
        return $this->hasMany(Catedra::class, 'id_grado', 'id_grado')
                    ->where('secciones_nombreSeccion', $this->nombreSeccion);
    }
    public static function findByCompositeKeyOrFail($idGrado, $nombreSeccion)
    {
        return self::where('id_grado', $idGrado)
                  ->where('nombreSeccion', $nombreSeccion)
                  ->firstOrFail();
    }

    //public function alumnos()
    //{
     //   return $this->hasMany(Alumno::class)
    //}

}
