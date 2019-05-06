<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;
    
    public function division()
    {
        return $this->belongsTo('App\Division');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
