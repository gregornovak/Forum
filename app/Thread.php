<?php

namespace App;

use App\User;
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
     * Get the user associated with this thread.
     */
    public function user()
    {
        return User::find($this->user_id);
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
