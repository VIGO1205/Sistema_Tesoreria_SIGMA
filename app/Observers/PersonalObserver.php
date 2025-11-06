<?php

namespace App\Observers;

use App\Models\Personal;
use App\Observers\Traits\LogsActions;

class PersonalObserver
{
    use LogsActions;
    /**
     * Handle the Personal "created" event.
     */
    public function created(Personal $personal): void
    {
        $this->logAction($personal, 'CREAR', $personal);
    }

    /**
     * Handle the Personal "updated" event.
     */
    public function updated(Personal $personal): void
    {
        if ($personal->isDirty('estado') && $personal->estado == 0){
            $this->logAction($personal, 'ELIMINAR', $personal, 'Registro marcado como inactivo');
        } else {
            $this->logAction($personal, 'EDITAR', $personal);
        }
    }

    /**
     * Handle the Personal "deleted" event.
     */
    public function deleted(Personal $personal): void
    {
        $this->logAction($personal, 'ELIMINAR', $personal, 'Eliminación física del registro');
    }

    /**
     * Handle the Personal "restored" event.
     */
    public function restored(Personal $personal): void
    {
        $this->logAction($personal, 'RESTAURAR', $personal);
    }
}
