<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroHistorico extends Model
{
    use HasFactory;

    protected $table = 'registro_historico'; 
    protected $primaryKey = 'id_registro_historico'; 
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_concepto_accion',
        'id_autor',
        'id_entidad_afectada',
        'tipo_entidad_afectada',
        'fecha_accion',
        'observacion',
        'estado',
    ];

    public $timestamps = false;

    protected $casts = [
        'fecha_accion' => 'datetime',
        'estado' => 'boolean'
    ];

    public function conceptoAccion()
    {
        return $this->belongsTo(ConceptoAccion::class, 'id_concepto_accion', 'id_concepto_accion');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'id_autor', 'id_usuario');
    }
    public function entidadAfectada()
    {
        return $this->morphTo('entidad_afectada', 'tipo_entidad_afectada', 'id_entidad_afectada');
    }



}
