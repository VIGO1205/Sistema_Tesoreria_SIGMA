<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso_Grado extends Model
{
    use HasFactory;

    protected $table = 'cursos_grados';
    protected $primaryKey = 'id_curso_grado';
    public $incrementing = true; 
    protected $keyType = 'int'; 
    protected $fillable = [
        'id_curso',
        'id_grado',
        'año_escolar',
        'estado',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }


}
