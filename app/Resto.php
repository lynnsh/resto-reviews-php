<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * The class that represents a Resto object.
 * 
 * @author Alena Shulzhenko
 * @version 2016-12-05
 */
class Resto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'genre', 'price', 'address', 
                           'latitude', 'longitude'];
    
    /**
     * The user that created this resto.
     * @return User the user that created this resto.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    /**
     * The reviews that belong to this resto.
     * @return the reviews that belong to this resto.
     */
    public function reviews() {
        return $this->hasMany('App\Review');
    }
    
    /**
     * Determines if the user can delete this resto,
     * which is true if this user created this resto.
     * @param \App\User $user the authenticated user.
     * @return true if the user can delete this resto; false otherwise.
     */
    public function userCanDelete(User $user) {
        return $user->id === $this->user_id;
    }
}
