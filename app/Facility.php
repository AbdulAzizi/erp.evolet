<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function attributes() 
    {
        return $this->hasMany('App\Attribute');
    }

    public function user() 
    {
        return $this->belongsTo('App\User');
    }
}
