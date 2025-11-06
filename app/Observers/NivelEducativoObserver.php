<?php

namespace App\Observers;

use App\Models\NivelEducativo;
use App\Observers\Traits\LogsActions;

class NivelEducativoObserver
{
    use LogsActions;
    /**
     * Handle the NivelEducativo "created" event.
     */
    public function created(NivelEducativo $nivelEducativo): void
    {
        $this->logAction($nivelEducativo, 'CREAR', $nivelEducativo);
    }

    /**
     * Handle the NivelEducativo "updated" event.
     */
    public function updated(NivelEducativo $nivelEducativo): void
    {
        if ($nivelEducativo->isDirty('estado') && $nivelEducativo->estado == 0){
            $this->logAction($nivelEducativo, 'ELIMINAR', $nivelEducativo, 'Registro marcado como inactivo');
        } else {
            $this->logAction($nivelEducativo, 'EDITAR', $nivelEducativo);
        }
    }

    /**
     * Handle the NivelEducativo "deleted" event.
     */
    public function deleted(NivelEducativo $nivelEducativo): void
    {
        $this->logAction($nivelEducativo, 'ELIMINAR', $nivelEducativo, 'Eliminación física del registro');
    }

    /**
     * Handle the NivelEducativo "restored" event.
     */
    public function restored(NivelEducativo $nivelEducativo): void
    {
        $this->logAction($nivelEducativo, 'RESTAURAR', $nivelEducativo);
    }
}
