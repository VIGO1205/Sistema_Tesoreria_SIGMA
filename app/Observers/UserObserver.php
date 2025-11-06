<?php

namespace App\Observers;

use App\Models\User;
use App\Observers\Traits\LogsActions;

class UserObserver
{
    use LogsActions;
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->logAction($user, 'CREAR', $user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->isDirty('estado') && $user->estado == 0){
            $this->logAction($user, 'ELIMINAR', $user, 'Registro marcado como inactivo');
        } else {
            $this->logAction($user, 'EDITAR', $user);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->logAction($user, 'ELIMINAR', $user, 'Eliminación física del registro');
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->logAction($user, 'RESTAURAR', $user);
    }
}
