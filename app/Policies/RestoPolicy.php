<?php

namespace App\Policies;

use App\User;
use App\Resto;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestoPolicy
{
    use HandlesAuthorization;
    

    /**
     * Determine whether the user can view the resto.
     *
     * @param  \App\User  $user
     * @param  \App\Resto  $resto
     * @return mixed
     */
    public function view(User $user, Resto $resto)
    {
        //
    }

    /**
     * Determine whether the user can create restos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

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

    /**
     * Determine whether the user can delete the resto.
     *
     * @param  \App\User  $user
     * @param  \App\Resto  $resto
     * @return mixed
     */
    public function delete(User $user, Resto $resto)
    {
        //
    }
}
