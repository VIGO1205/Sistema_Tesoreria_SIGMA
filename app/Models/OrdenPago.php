<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenPago extends Model
{
    use HasFactory;

    protected $table = 'ordenes_pago';

    protected $primaryKey = 'id_orden';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'codigo_orden',
        'id_alumno',
        'id_matricula',
        // id_deuda eliminado - ahora cada detalle tiene su id_deuda
        'monto_total',
        'numero_cuenta',
        'fecha_orden_pago',
        'fecha_vencimiento',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_orden_pago' => 'date',
            'fecha_vencimiento' => 'date',
            'monto_total' => 'decimal:2',
        ];
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'id_matricula', 'id_matricula');
    }

    // Relación eliminada - ahora los detalles tienen la relación con deudas
    
    public function detalles()
    {
        return $this->hasMany(DetalleOrdenPago::class, 'id_orden', 'id_orden');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_orden', 'id_orden');
    }

    /**
     * Verifica si la orden está completamente pagada
     */
    public function estaPagadaCompletamente()
    {
        $totalPagado = $this->pagos()->where('estado', true)->sum('monto');
        return $totalPagado >= $this->monto_total;
    }
}
