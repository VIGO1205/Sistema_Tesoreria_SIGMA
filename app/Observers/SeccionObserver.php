<?php

namespace App\Observers;

use App\Models\Seccion;
use App\Observers\Traits\LogsActions;

class SeccionObserver
{
    use LogsActions;
    /**
     * Handle the Seccion "created" event.
     */
    public function created(Seccion $seccion): void
    {
        $this->logAction($seccion, 'CREAR', $seccion);
    }

    /**
     * Handle the Seccion "updated" event.
     */
    public function updated(Seccion $seccion): void
    {
        if ($seccion->isDirty('estado') && $seccion->estado == 0){
            $this->logAction($seccion, 'ELIMINAR', $seccion, 'Registro marcado como inactivo');
        } else {
            $this->logAction($seccion, 'EDITAR', $seccion);
        }
    }

    /**
     * Handle the Seccion "deleted" event.
     */
    public function deleted(Seccion $seccion): void
    {
        $this->logAction($seccion, 'ELIMINAR', $seccion, 'Eliminación física del registro');
    }

    /**
     * Handle the Seccion "restored" event.
     */
    public function restored(Seccion $seccion): void
    {
        $this->logAction($seccion, 'RESTAURAR', $seccion);
    }
}
