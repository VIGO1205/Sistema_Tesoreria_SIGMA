<?php

namespace App\Observers;

use App\Models\ConceptoAccion;
use App\Observers\Traits\LogsActions;

class ConceptoAccionObserver
{
    use LogsActions;
    /**
     * Handle the ConceptoAccion "created" event.
     */
    public function created(ConceptoAccion $conceptoAccion): void
    {
        $this->logAction($conceptoAccion, 'CREAR', $conceptoAccion);
    }

    /**
     * Handle the ConceptoAccion "updated" event.
     */
    public function updated(ConceptoAccion $conceptoAccion): void
    {
        if ($conceptoAccion->isDirty('estado') && $conceptoAccion->estado == 0){
            $this->logAction($conceptoAccion, 'ELIMINAR', $conceptoAccion, 'Registro marcado como inactivo');
        } else {
            $this->logAction($conceptoAccion, 'EDITAR', $conceptoAccion);
        }
    }

    /**
     * Handle the ConceptoAccion "deleted" event.
     */
    public function deleted(ConceptoAccion $conceptoAccion): void
    {
        $this->logAction($conceptoAccion, 'ELIMINAR', $conceptoAccion, 'Eliminación física del registro');
    }

    /**
     * Handle the ConceptoAccion "restored" event.
     */
    public function restored(ConceptoAccion $conceptoAccion): void
    {
        $this->logAction($conceptoAccion, 'RESTAURAR', $conceptoAccion);
    }
}
