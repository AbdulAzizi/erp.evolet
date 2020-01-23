<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessTask extends Model
{
    public $timestamps = false;

    public $guarded = [];

    public function forms()
    {
        return $this->belongsToMany('App\Form', 'process_task_form', 'process_task_id');
    }

    public function watchers()
    {
        return $this->belongsToMany('App\Position', 'process_task_watchers', 'process_task_id', 'position_id');
    }

    public function polls()
    {
        return $this->belongsToMany('App\Question', 'process_task_question', 'process_task_id', 'question_id');
    }

    public function process()
    {
        return $this->belongsTo('App\Process');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }
}
