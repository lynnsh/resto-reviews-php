<?php

namespace App\Policies;

use App\User;
use App\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine if the given user can edit the given review.
     *
     * @param  User  $user
     * @param  Review  $review
     * @return boolean
     */
    public function edit(User $user, Review $review) {
        return $user->id === $review->user_id;
    }
}
