<?php

namespace App;

use App\Post;
use App\Thread;

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
        'nickname', 'num_of_posts', 'email', 'password',
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
     * User can have many threads.
     */
    public function threads() 
    {
        return $this->hasMany('App\Thread');
    }

    /**
     * User can have many posts.
    */
    public function posts() 
    {
        return $this->hasMany('App\Post');
    }
}
