<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function empolyees(){
        return $this->hasMany('App\Employee');
    }
}
