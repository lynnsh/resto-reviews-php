<?php

namespace App\Policies;

use App\User;
use App\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * The Policy that determines user access to Review CRUD functionalities.
 * 
 * @author Alena Shulzhenko
 * @version 2016-12-09
 */
class ReviewPolicy {
    use HandlesAuthorization;
    
    /**
     * Determine if the given user can edit the given review.
     *
     * @param  User  $user the authenticated user.
     * @param  Review  $review the review to update.
     * @return true if the user created this review, false otherwise.
     */
    public function edit(User $user, Review $review) {
        return $user->id === $review->user_id;
    }
}
