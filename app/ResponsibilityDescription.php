<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponsibilityDescription extends Model
{
    public $timestamps = false;

    public $guarded = [];

    public function responsibility()
    {
        return $this->belongsToMany('App\Responsibility');
    }
}
