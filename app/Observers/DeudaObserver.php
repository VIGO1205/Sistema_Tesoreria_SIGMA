<?php

namespace App\Observers;

use App\Models\Deuda;
use App\Observers\Traits\LogsActions;

class DeudaObserver
{
    use LogsActions;
    /**
     * Handle the Deuda "created" event.
     */
    public function created(Deuda $deuda): void
    {
        $this->logAction($deuda, 'CREAR', $deuda);
    }

    /**
     * Handle the Deuda "updated" event.
     */
    public function updated(Deuda $deuda): void
    {
        if ($deuda->isDirty('estado') && $deuda->estado == 0){
            $this->logAction($deuda, 'ELIMINAR', $deuda, 'Registro marcado como inactivo');
        } else {
            $this->logAction($deuda, 'EDITAR', $deuda);
        }
    }

    /**
     * Handle the Deuda "deleted" event.
     */
    public function deleted(Deuda $deuda): void
    {
        $this->logAction($deuda, 'ELIMINAR', $deuda, 'Eliminación física del registro');
    }

    /**
     * Handle the Deuda "restored" event.
     */
    public function restored(Deuda $deuda): void
    {
        $this->logAction($deuda, 'RESTAURAR', $deuda);
    }
}
