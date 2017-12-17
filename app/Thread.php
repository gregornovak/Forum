<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get threads.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

    /**
     * Get posts.
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    /**
     * Get number of posts for this thread.
     */
    public function numberOfPosts()
    {
        return $this->posts()->count();
    }

    /**
     * Get all threads.
     */
    public function index()
    {
        return static::all();
    }
}
