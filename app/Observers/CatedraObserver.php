<?php

namespace App\Observers;

use App\Models\Catedra;
use App\Observers\Traits\LogsActions;

class CatedraObserver
{
    use LogsActions;
    /**
     * Handle the Catedra "created" event.
     */
    public function created(Catedra $catedra): void
    {
        $this->logAction($catedra, 'CREAR', $catedra);
    }

    /**
     * Handle the Catedra "updated" event.
     */
    public function updated(Catedra $catedra): void
    {
        if ($catedra->isDirty('estado') && $catedra->estado == 0){
            $this->logAction($catedra, 'ELIMINAR', $catedra, 'Registro marcado como inactivo');
        } else {
            $this->logAction($catedra, 'EDITAR', $catedra);
        }
    }

    /**
     * Handle the Catedra "deleted" event.
     */
    public function deleted(Catedra $catedra): void
    {
        $this->logAction($catedra, 'ELIMINAR', $catedra, 'Eliminación física del registro');
    }

    /**
     * Handle the Catedra "restored" event.
     */
    public function restored(Catedra $catedra): void
    {
        $this->logAction($catedra, 'RESTAURAR', $catedra);
    }
}
