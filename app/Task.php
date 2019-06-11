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
        return $this->belongsTo('App\Employee', 'responsible_id');
    }

    public function watchers()
    {
        return $this->belongsToMany('App\Employee', 'task_watchers');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }
}
