<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'rating', 'content', 'user_id' ];
    
    /**
     * The user that created this review.
     * @return User the user that created this review.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    /**
     * The resto this review is created for.
     * @return the resto this review is created for.
     */
    public function resto() {
        return $this->belongsTo('App\Resto');
    }
    
    /**
     * Determines if the user can edit this review,
     * which is true if this user created this review.
     * @param \App\User $user the authenticated user.
     * @return true if the user can edit this review; false otherwise.
     */
    public function userCanEdit(User $user) {
        return $user->id === $this->user_id;
    }
}
