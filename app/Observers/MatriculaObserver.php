<?php

namespace App\Observers;

use App\Models\Matricula;
use App\Observers\Traits\LogsActions;

class MatriculaObserver
{
    use LogsActions;
    /**
     * Handle the Matricula "created" event.
     */
    public function created(Matricula $matricula): void
    {
        $this->logAction($matricula, 'CREAR', $matricula);
    }

    /**
     * Handle the Matricula "updated" event.
     */
    public function updated(Matricula $matricula): void
    {
        if ($matricula->isDirty('estado') && $matricula->estado == 0){
            $this->logAction($matricula, 'ELIMINAR', $matricula, 'Registro marcado como inactivo');
        } else {
            $this->logAction($matricula, 'EDITAR', $matricula);
        }
    }

    /**
     * Handle the Matricula "deleted" event.
     */
    public function deleted(Matricula $matricula): void
    {
        $this->logAction($matricula, 'ELIMINAR', $matricula, 'Eliminación física del registro');
    }

    /**
     * Handle the Matricula "restored" event.
     */
    public function restored(Matricula $matricula): void
    {
        $this->logAction($matricula, 'RESTAURAR', $matricula);
    }
}
