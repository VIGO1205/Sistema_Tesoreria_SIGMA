<?php

namespace App\Observers;

use App\Models\ConceptoPago;
use App\Observers\Traits\LogsActions;

class ConceptoPagoObserver
{
    use LogsActions;
    /**
     * Handle the ConceptoPago "created" event.
     */
    public function created(ConceptoPago $conceptoPago): void
    {
        $this->logAction($conceptoPago, 'CREAR', $conceptoPago);
    }

    /**
     * Handle the ConceptoPago "updated" event.
     */
    public function updated(ConceptoPago $conceptoPago): void
    {
        if ($conceptoPago->isDirty('estado') && $conceptoPago->estado == 0){
            $this->logAction($conceptoPago, 'ELIMINAR', $conceptoPago, 'Registro marcado como inactivo');
        } else {
            $this->logAction($conceptoPago, 'EDITAR', $conceptoPago);
        }
    }

    /**
     * Handle the ConceptoPago "deleted" event.
     */
    public function deleted(ConceptoPago $conceptoPago): void
    {
        $this->logAction($conceptoPago, 'ELIMINAR', $conceptoPago, 'Eliminación física del registro');
    }

    /**
     * Handle the ConceptoPago "restored" event.
     */
    public function restored(ConceptoPago $conceptoPago): void
    {
        $this->logAction($conceptoPago, 'RESTAURAR', $conceptoPago);
    }
}
