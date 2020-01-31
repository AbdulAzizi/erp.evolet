<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['label', 'name', 'division_id'];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function responsibilities()
    {
        return $this->hasMany('App\Responsibility');
    }

    public function division()
    {
        return $this->belongsTo('App\Division');
    }

    public function processTask()
    {
        return $this->hasMany('App\ProcessTask');
    }
}
