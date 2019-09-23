<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['name', 'user_id'];

    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function user()
    {
        return $this->belongsToMany('App\User');
    }
}