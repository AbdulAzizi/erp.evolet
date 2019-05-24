<?php

namespace App;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;


class Division extends Model
{
    use NodeTrait;

    public $timestamps = false;
    
    public function head()
    {
        return $this->belongsTo('App\Employee');
    }

    public function employees()
    {
        return $this->hasMany('App\Employee');
    }
}
