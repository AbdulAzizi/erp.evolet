<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // protected $with = ['responsible.user','from'];

    public function from()
    {
        return $this->morphTo();
    }

    public function responsible()
    {
        return $this->belongsTo('App\User', 'responsible_id');
    }

    public function watchers()
    {
        return $this->belongsToMany('App\User', 'task_watchers');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
}
