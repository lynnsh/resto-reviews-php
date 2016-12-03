<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'genre', 'price', 'address', 
                           'latitude', 'longitude'];
    
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    public function reviews() {
        return $this->hasMany('App\Review');
    }
    
    public function userCanDelete(User $user) {
        return $user->id === $this->user_id;
    }
}
