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

    public function backTethers()
    {
        return $this->hasMany('App\Tether', 'to_process_id');
    }

    public function frontTethers()
    {
        return $this->hasMany('App\Tether', 'from_process_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product','process_id');
    }

}
