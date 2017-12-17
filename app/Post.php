<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable./
     *
     * @var array
     */
    protected $fillable = [
        'body', 'user_id', 'thread_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the posts.
     */
    public function posts()
    {
        return $this->belongsTo('App\Thread');
    }
}
