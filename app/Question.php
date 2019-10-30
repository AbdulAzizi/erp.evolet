<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps = false;

    protected $appends = ['answers_count'];
    protected $fillable = ['body'];

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function task()
    {
        return $this->belongsToMany('App\Task');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function getAnswersCountAttribute()
    {
        return $this->questionTasks()->first()->answers()->count();
    }

    public function questionTasks()
    {
        return $this->hasMany('App\QuestionTask');
    }
}
