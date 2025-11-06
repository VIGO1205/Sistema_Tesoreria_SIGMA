<?php

namespace App\Observers;

use App\Models\Alumno;
use App\Observers\Traits\LogsActions;

class AlumnoObserver
{
    use LogsActions;
    /**
     * Handle the Alumno "created" event.
     */
    public function created(Alumno $alumno): void
    {
        $this->logAction($alumno, 'CREAR', $alumno);
    }

    /**
     * Handle the Alumno "updated" event.
     */
    public function updated(Alumno $alumno): void
    {
        if ($alumno->isDirty('estado') && $alumno->estado == 0){
            $this->logAction($alumno, 'ELIMINAR', $alumno, 'Registro marcado como inactivo');
        } else {
            $this->logAction($alumno, 'EDITAR', $alumno);
        }
    }

    /**
     * Handle the Alumno "deleted" event.
     */
    public function deleted(Alumno $alumno): void
    {
        $this->logAction($alumno, 'ELIMINAR', $alumno, 'Eliminación física del registro');
    }

    /**
     * Handle the Alumno "restored" event.
     */
    public function restored(Alumno $alumno): void
    {
        $this->logAction($alumno, 'RESTAURAR', $alumno);
    }
}
