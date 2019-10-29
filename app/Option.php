<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $timestamps = false;

    protected $fillable = ['body'];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','question_answers');
    }
}
