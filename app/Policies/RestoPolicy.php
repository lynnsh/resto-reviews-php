<?php

namespace App\Policies;

use App\User;
use App\Resto;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the resto.
     *
     * @param  User  $user
     * @param  Resto  $resto
     * @return boolean
     */
    public function update(User $user, Resto $resto) {
        return isset($user);
    }
    
    /**
     * Determine if the given user can delete the given resto.
     *
     * @param  User  $user
     * @param  Resto  $resto
     * @return boolean
     */
    public function delete(User $user, Resto $resto) {
        return $user->id === $resto->user_id;
    }
}
