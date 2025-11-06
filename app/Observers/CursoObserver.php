<?php

namespace App\Observers;

use App\Models\Curso;
use App\Observers\Traits\LogsActions;

class CursoObserver
{
    use LogsActions;
    /**
     * Handle the Curso "created" event.
     */
    public function created(Curso $curso): void
    {
        $this->logAction($curso, 'CREAR', $curso);
    }

    /**
     * Handle the Curso "updated" event.
     */
    public function updated(Curso $curso): void
    {
        if ($curso->isDirty('estado') && $curso->estado == 0){
            $this->logAction($curso, 'ELIMINAR', $curso, 'Registro marcado como inactivo');
        } else {
            $this->logAction($curso, 'EDITAR', $curso);
        }
    }

    /**
     * Handle the Curso "deleted" event.
     */
    public function deleted(Curso $curso): void
    {
        $this->logAction($curso, 'ELIMINAR', $curso, 'Eliminación física del registro');
    }

    /**
     * Handle the Curso "restored" event.
     */
    public function restored(Curso $curso): void
    {
        $this->logAction($curso, 'RESTAURAR', $curso);
    }
}
