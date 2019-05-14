<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;
    
    public function division()
    {
        return $this->belongsTo('App\Division');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function responsibility()
    {
        return $this->belongsTo('App\Responsibility');
    }

    public function tasks()
    {
        return $this->morphMany('App\Task', 'from');
    }
}
