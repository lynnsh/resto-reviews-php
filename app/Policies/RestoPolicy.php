<?php

namespace App\Policies;

use App\User;
use App\Resto;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * The Policy that determines user access to Resto CRUD functionalities.
 * 
 * @author Alena Shulzhenko
 * @version 2016-12-09
 */
class RestoPolicy {
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the resto.
     *
     * @param  User  $user the authenticated user.
     * @param  Resto  $resto the resto to update.
     * @return true if the user is authenticated, false otherwise.
     */
    public function update(User $user, Resto $resto) {
        return isset($user);
    }
    
    /**
     * Determine if the given user can delete the given resto.
     *
     * @param  User  $user the authenticated user.
     * @param  Resto  $resto the resto to delete.
     * @return true if the user created this resto, false otherwise.
     */
    public function delete(User $user, Resto $resto) {
        return $user->id === $resto->user_id;
    }
}
