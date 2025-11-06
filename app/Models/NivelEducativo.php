<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelEducativo extends Model
{
    use HasFactory;

    protected $table = "niveles_educativos";

    protected $primaryKey = "id_nivel";

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre_nivel',
        'descripcion',
        'estado',
    ];
}
