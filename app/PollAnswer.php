<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PollAnswer extends Pivot
{
    protected $with = ['user','option'];
    protected $table = 'question_answers';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function option()
    {
        return $this->belongsTo('App\Option');
    }

    public function questionTask()
    {
        return $this->belongsTo('App\QuestionTask');
    }

}
