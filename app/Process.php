<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    public $guarded = [];

    public $timestamps = false;

    protected $with = ['frontTethers', 'backTethers'];

    public function tasks()
    {
        return $this->morphMany('App\Task', 'from');
    }

    public function backTethers()
    {
        return $this->hasMany('App\Tether', 'to_process_id');
    }

    public function frontTethers()
    {
        return $this->hasMany('App\Tether', 'from_process_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'process_id');
    }

    public function product()
    {
        return $this->belongsToMany('App\Product');
    }
}
