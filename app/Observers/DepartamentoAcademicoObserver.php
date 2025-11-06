<?php

namespace App\Observers;

use App\Models\DepartamentoAcademico;
use App\Observers\Traits\LogsActions;

class DepartamentoAcademicoObserver
{
    use LogsActions;
    /**
     * Handle the DepartamentoAcademico "created" event.
     */
    public function created(DepartamentoAcademico $departamentoAcademico): void
    {
        $this->logAction($departamentoAcademico, 'CREAR', $departamentoAcademico);
    }

    /**
     * Handle the DepartamentoAcademico "updated" event.
     */
    public function updated(DepartamentoAcademico $departamentoAcademico): void
    {
        if ($departamentoAcademico->isDirty('estado') && $departamentoAcademico->estado == 0){
            $this->logAction($departamentoAcademico, 'ELIMINAR', $departamentoAcademico, 'Registro marcado como inactivo');
        } else {
            $this->logAction($departamentoAcademico, 'EDITAR', $departamentoAcademico);
        }
    }

    /**
     * Handle the DepartamentoAcademico "deleted" event.
     */
    public function deleted(DepartamentoAcademico $departamentoAcademico): void
    {
        $this->logAction($departamentoAcademico, 'ELIMINAR', $departamentoAcademico, 'Eliminación física del registro');
    }

    /**
     * Handle the DepartamentoAcademico "restored" event.
     */
    public function restored(DepartamentoAcademico $departamentoAcademico): void
    {
        $this->logAction($departamentoAcademico, 'RESTAURAR', $departamentoAcademico);
    }
}
