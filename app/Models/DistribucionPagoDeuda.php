<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribucionPagoDeuda extends Model
{
    use HasFactory;

    protected $table = 'distribucion_pago_deuda';
    protected $primaryKey = 'id_distribucion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pago',
        'id_deuda',
        'monto_aplicado',
    ];

    protected function casts(): array
    {
        return [
            'monto_aplicado' => 'decimal:2',
        ];
    }

    // Relación con Pago
    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id_pago');
    }

    // Relación con Deuda
    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'id_deuda', 'id_deuda');
    }
}
