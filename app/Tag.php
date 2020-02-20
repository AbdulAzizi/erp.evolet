<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->belongsToMany('App\Task');
    }

    public function division()
    {
        return $this->belongsTo('App\Division');
    }

}
