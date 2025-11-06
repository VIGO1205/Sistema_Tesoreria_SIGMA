<?php

namespace App\Observers;

use App\Models\Pago;
use App\Observers\Traits\LogsActions;

class PagoObserver
{
    use LogsActions;
    /**
     * Handle the Pago "created" event.
     */
    public function created(Pago $pago): void
    {
        $this->logAction($pago, 'CREAR', $pago);
    }

    /**
     * Handle the Pago "updated" event.
     */
    public function updated(Pago $pago): void
    {
        if ($pago->isDirty('estado') && $pago->estado == 0){
            $this->logAction($pago, 'ELIMINAR', $pago, 'Registro marcado como inactivo');
        } else {
            $this->logAction($pago, 'EDITAR', $pago);
        }
    }

    /**
     * Handle the Pago "deleted" event.
     */
    public function deleted(Pago $pago): void
    {
        $this->logAction($pago, 'ELIMINAR', $pago, 'Eliminación física del registro');
    }

    /**
     * Handle the Pago "restored" event.
     */
    public function restored(Pago $pago): void
    {
        $this->logAction($pago, 'RESTAURAR', $pago);
    }
}
