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

    public function fromTether()
    {
        return $this->hasOne('App\Tether', 'from_process_id');
    }

    public function toTether()
    {
        return $this->hasOne('App\Tether', 'to_process_id');
    }

}
