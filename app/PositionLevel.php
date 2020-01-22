<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PositionLevel extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
