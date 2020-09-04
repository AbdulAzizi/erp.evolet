<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Repitition extends Model
{
    public $guarded = [];

    public $timestamps = false;

    public function tasks() 
    {
      return $this->hasMany('App\Task', 'repeat_id');
    }
} 
