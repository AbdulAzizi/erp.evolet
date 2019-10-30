<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionTask extends Pivot
{
    protected $table = "question_task";   
    public function task()
    {
        return $this->belongsTo('App\Task');
    }  

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function answers()
    {
        return $this->hasMany('App\PollAnswer','question_task_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','question_answers','question_task_id','user_id')->withPivot('option_id');
    }
} 
