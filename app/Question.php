<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps = false;

    protected $fillable = ['body'];

    public function options()
    {
        return $this->hasMany('App\QuestionOption');
    }

    public function task()
    {
        return $this->belongsToMany('App\Task');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
