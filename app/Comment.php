<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'body', 'commentable_id', 'commentable_type'];

    protected $with = ['sender'];

    public function commentable()
    {
        return $this->morphTo();
    }
    
    // public function products()
    // {
    //     return $this->morphedByMany('App\Product','commetable');
    // }

    // public function tasks()
    // {
    //     return $this->morphedByMany('App\Task','commentable');
    // }

    // public function chats()
    // {
    //     return $this->morphedByMany('App\Chat','commentable');
    // }

    // public function users()
    // {
    //     return $this->morphedByMany('App\User','commentable');
    // }

    public function sender()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
