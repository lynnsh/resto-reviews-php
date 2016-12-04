<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'latitude', 'longitude', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * The list of restos that this user created.
     * @return restos that this user created.
     */
    public function restos() {
        return $this->hasMany('App\Resto');
    }
    
    /**
     * The list of reviews that this user created.
     * @return reviews that this user created.
     */
    public function reviews() {
        return $this->hasMany('App\Review');
    }
}
