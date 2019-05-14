<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    public $timestamps = false;
    
    public function tasks()
    {
        return $this->morphMany('App\Task', 'from');
    }
}
