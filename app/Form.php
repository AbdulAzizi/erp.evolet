<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public $timestamps = false;

    public $guarded = [];

    public function fields()
    {
        return $this->belongsToMany(FormField::class, 'field_form', 'form_id', 'field_id')->withPivot('required','multiple');
    }

    public function task()
    {
        return $this->belongsToMany('App\Task');
    }

    public function tethers()
    {
        return $this->belongsToMany('App\Tether');
    }
}
