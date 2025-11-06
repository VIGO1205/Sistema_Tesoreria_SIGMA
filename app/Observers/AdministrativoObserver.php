<?php

namespace App\Observers;

use App\Models\Administrativo;
use App\Observers\Traits\LogsActions;

class AdministrativoObserver
{
    use LogsActions;
    /**
     * Handle the Administrativo "created" event.
     */
    public function created(Administrativo $administrativo): void
    {
        $this->logAction($administrativo, 'CREAR', $administrativo);
    }

    /**
     * Handle the Administrativo "updated" event.
     */
    public function updated(Administrativo $administrativo): void
    {
        if ($administrativo->isDirty('estado') && $administrativo->estado == 0){
            $this->logAction($administrativo, 'ELIMINAR', $administrativo, 'Registro marcado como inactivo');
        } else {
            $this->logAction($administrativo, 'EDITAR', $administrativo);
        }
    }

    /**
     * Handle the Administrativo "deleted" event.
     */
    public function deleted(Administrativo $administrativo): void
    {
        $this->logAction($administrativo, 'ELIMINAR', $administrativo, 'Eliminación física del registro');
    }

    /**
     * Handle the Administrativo "restored" event.
     */
    public function restored(Administrativo $administrativo): void
    {
        $this->logAction($administrativo, 'RESTAURAR', $administrativo);
    }
}
