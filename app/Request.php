<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    public $guarded = [];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function parameters() 
    {
        return $this->hasMany('App\Parameter');
    }
}
