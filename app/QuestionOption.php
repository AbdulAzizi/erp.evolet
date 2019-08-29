<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    public $timestamps = false;

    protected $fillable = ['body'];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }
}
