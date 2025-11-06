<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Politica extends Model
{
    use HasFactory;

    protected $table = 'politica';

    protected $primaryKey = 'id_politica';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_concepto',
        'nombre',
        'tipo',
        'porcentaje',
        'dias_minimo',
        'dias_maximo',
        'condiciones',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'porcentaje' => 'decimal:2',
            'dias_minimo' => 'integer',
            'dias_maximo' => 'integer',
            'estado' => 'boolean',
        ];
    }

    public function conceptoPago()
    {
        return $this->belongsTo(ConceptoPago::class, 'id_concepto', 'id_concepto');
    }

    public function detallesOrdenPago()
    {
        return $this->hasMany(DetalleOrdenPago::class, 'id_politica', 'id_politica');
    }
}
