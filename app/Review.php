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
    protected $fillable = ['title', 'rating', 'content', 'user_id' ]; //can assign??
    
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    public function resto() {
        return $this->belongsTo('App\Resto');
    }
}
