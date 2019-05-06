<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public function mnns()
    {
        return $this->belongsToMany('App\Mnn');
    }
}
