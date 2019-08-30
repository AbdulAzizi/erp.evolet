<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    public $timestamp = false;

    public function question()
    {
        return $this->belongsTo('App\Question');
    }
}
