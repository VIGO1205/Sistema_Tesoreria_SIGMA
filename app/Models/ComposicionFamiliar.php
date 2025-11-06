<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComposicionFamiliar extends Model
{
    use HasFactory;

    protected $table = 'composiciones_familiares';
    protected $primaryKey = ['id_alumno', 'id_familiar'];
    public $incrementing = false;

    protected $fillable = [
        'id_alumno',
        'id_familiar',
        'parentesco',
        'estado',
    ];
}
