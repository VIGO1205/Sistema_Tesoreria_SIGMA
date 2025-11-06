<?php

namespace App\Observers;

use App\Models\DetallePago;
use App\Observers\Traits\LogsActions;

class DetallePagoObserver
{
    use LogsActions;
    /**
     * Handle the DetallePago "created" event.
     */
    public function created(DetallePago $detallePago): void
    {
        $this->logAction($detallePago, 'CREAR', $detallePago);
    }

    /**
     * Handle the DetallePago "updated" event.
     */
    public function updated(DetallePago $detallePago): void
    {
        if ($detallePago->isDirty('estado') && $detallePago->estado == 0){
            $this->logAction($detallePago, 'ELIMINAR', $detallePago, 'Registro marcado como inactivo');
        } else {
            $this->logAction($detallePago, 'EDITAR', $detallePago);
        }
    }

    /**
     * Handle the DetallePago "deleted" event.
     */
    public function deleted(DetallePago $detallePago): void
    {
        $this->logAction($detallePago, 'ELIMINAR', $detallePago, 'Eliminación física del registro');
    }

    /**
     * Handle the DetallePago "restored" event.
     */
    public function restored(DetallePago $detallePago): void
    {
        $this->logAction($detallePago, 'RESTAURAR', $detallePago);
    }
}
