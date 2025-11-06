<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartamentoAcademico extends Model
{
    use HasFactory;

    protected $table = "departamentos_academicos";
    protected $primaryKey = 'id_departamento';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'estado',
    ];
}
