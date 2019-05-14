<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mnn extends Model
{
    public function forms()
    {
        return $this->belongsToMany('App\Form');
    }
}
