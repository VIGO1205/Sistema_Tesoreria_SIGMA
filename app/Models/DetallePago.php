<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePago extends Model
{
    use HasFactory;

    protected $table = 'detalle_pago'; 

    protected $primaryKey = 'id_detalle';
    public $incrementing = true;

    protected $fillable = [
        'id_detalle',
        'id_pago',
        'nro_recibo',
        'fecha_pago',
        'monto',
        'observacion',
        'estado',
        'metodo_pago',
        'voucher_path',
        'voucher_texto',
        'estado_validacion',
        'validado_por_ia',
        'porcentaje_confianza',
        'razon_ia'
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2',
        'estado' => 'boolean',
        'validado_por_ia' => 'boolean',
        'porcentaje_confianza' => 'decimal:2'
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id_pago');
    }

    public function estaValidado()
    {
        return $this->estado_validacion === 'validado';
    }
}