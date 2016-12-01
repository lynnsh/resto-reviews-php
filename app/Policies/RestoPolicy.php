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
     * @param  \App\User  $user
     * @param  \App\Resto  $resto
     * @return mixed
     */
    public function update(User $user, Resto $resto)
    {
        return isset($user);
    }
}
