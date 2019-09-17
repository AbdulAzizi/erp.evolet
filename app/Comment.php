<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'body'];

    public function products()
    {
        return $this->morphedByMany('App\Product','commetable');
    }

    public function tasks()
    {
        return $this->morphedByMany('App\Task','commentable');
    }

    public function chats()
    {
        return $this->morphedByMany('App\Chat','commentable');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
