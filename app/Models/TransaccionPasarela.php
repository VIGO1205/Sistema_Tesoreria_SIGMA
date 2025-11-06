<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaccionPasarela extends Model
{
    use HasFactory;

    protected $table = 'transacciones_pasarela';
    
    protected $primaryKey = 'id_transaccion';
    
    protected $fillable = [
        'id_orden',
        'metodo_pago',
        'numero_operacion',
        'fecha_transaccion',
        'monto',
        'datos_adicionales',
        'estado',
        'voucher_path',
        'validado_por',
        'fecha_validacion',
        'id_pago_generado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_transaccion' => 'datetime',
            'fecha_validacion' => 'datetime',
            'monto' => 'decimal:2',
            'datos_adicionales' => 'array',
        ];
    }

    // Relaciones
    public function ordenPago()
    {
        return $this->belongsTo(OrdenPago::class, 'id_orden', 'id_orden');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'validado_por', 'id');
    }

    public function pagoGenerado()
    {
        return $this->belongsTo(Pago::class, 'id_pago_generado', 'id_pago');
    }
}
