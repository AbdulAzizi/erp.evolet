<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    public $guarded = [];

    public $timestamps = false;

    public function request()
    {
        return $this->belongsTo('App\Request');
    }
}
