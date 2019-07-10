<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    public $timestamps = false;

    public function forms()
    {
        return $this->belongsToMany('App\Form');
    }

    public function fields()
    {
        return $this->belongsToMany('App\Product','product_values');
    }
}
