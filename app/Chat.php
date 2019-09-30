<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title', 'admin_id'
    ];

    public function participants()
    {
        return $this->belongsToMany('App\User','chat_participants');
    }
    
    public function admin()
    {
        return $this->belongsTo('App\User');
    }
    
    public function comments()
    {
        return $this->morphMany('App\Comment','commentable');
    }
}
