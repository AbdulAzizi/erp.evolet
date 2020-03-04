<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponsibilityDescription extends Model
{
    public $timestamps = false;

    public $guarded = [];

    public function responsibility()
    {
        return $this->belongsTo('App\Responsibility');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
