<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $table = "personal";

    protected $primaryKey = 'id_personal';
    public $keyType = 'int';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id_usuario',
        'codigo_personal',
        'apellido_paterno',
        'apellido_materno',
        'primer_nombre',
        'otros_nombres',
        'dni',
        'direccion',
        'estado_civil',
        'telefono',
        'seguro_social',
        'fecha_ingreso',
        'departamento',
        'categoria',
        'estado',
        'id_departamento'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function departamentos_academicos()
    {
        return $this->belongsTo(DepartamentoAcademico::class, 'id_departamento', 'id_departamento');
    }

    protected function casts(): array
    {
        return [
            'fecha_ingreso' => 'date', 
            'estado' => 'boolean',     
        ];
    }


}
