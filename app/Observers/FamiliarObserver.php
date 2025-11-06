<?php

namespace App\Observers;

use App\Models\Familiar;
use App\Observers\Traits\LogsActions;

class FamiliarObserver
{
    use LogsActions;
    /**
     * Handle the Familiar "created" event.
     */
    public function created(Familiar $familiar): void
    {
        $this->logAction($familiar, 'CREAR', $familiar);
    }

    /**
     * Handle the Familiar "updated" event.
     */
    public function updated(Familiar $familiar): void
    {
        if ($familiar->isDirty('estado') && $familiar->estado == 0){
            $this->logAction($familiar, 'ELIMINAR', $familiar, 'Registro marcado como inactivo');
        } else {
            $this->logAction($familiar, 'EDITAR', $familiar);
        }
    }

    /**
     * Handle the Familiar "deleted" event.
     */
    public function deleted(Familiar $familiar): void
    {
        $this->logAction($familiar, 'ELIMINAR', $familiar, 'Eliminación física del registro');
    }

    /**
     * Handle the Familiar "restored" event.
     */
    public function restored(Familiar $familiar): void
    {
        $this->logAction($familiar, 'RESTAURAR', $familiar);
    }
}
