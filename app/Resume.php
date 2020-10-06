<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{

    protected $guarded = [];

    public function owner()
    {

        return $this->belongsToMany('App\User');
    }

    public function educations()
    {
        return $this->hasMany('App\Education');
    }


    public function jobs()
    {
        return $this->hasMany('App\Job');
    }


    public function families()
    {
        return $this->hasMany('App\Family');
    }


    public function languages()
    {
        return $this->hasMany('App\Language');
    }


    public function achievments()
    {
        return $this->hasMany('App\Achievment');
    }

    public function skills()
    {
        return $this->hasMany('App\Skill');
    }

    public function hobbies()
    {
        return $this->hasMany('App\Hobbie');
    }

    public function informations()
    {
        return $this->hasMany('App\Extrainformation');
    }
}
