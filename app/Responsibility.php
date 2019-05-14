<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    public function empolyees(){
        return $this->hasMany('App\Employee');
    }
}
