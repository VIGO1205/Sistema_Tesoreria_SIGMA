<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrdenPago extends Model
{
    use HasFactory;

    protected $table = 'detalle_orden_pago';

    protected $primaryKey = 'id_detalle';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_orden',
        'id_deuda',
        'id_concepto',
        'id_politica',
        'monto_base',
        'monto_ajuste',
        'monto_subtotal',
        'descripcion_ajuste',
    ];

    protected function casts(): array
    {
        return [
            'monto_base' => 'decimal:2',
            'monto_ajuste' => 'decimal:2',
            'monto_subtotal' => 'decimal:2',
        ];
    }

    public function ordenPago()
    {
        return $this->belongsTo(OrdenPago::class, 'id_orden', 'id_orden');
    }

    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'id_deuda', 'id_deuda');
    }

    public function conceptoPago()
    {
        return $this->belongsTo(ConceptoPago::class, 'id_concepto', 'id_concepto');
    }

    public function politica()
    {
        return $this->belongsTo(Politica::class, 'id_politica', 'id_politica');
    }
}
