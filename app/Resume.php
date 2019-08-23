<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{

    protected $fillable = ['birthday', 'user_id', 'phone', 'male_female', 'military_status'];

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function educations()
    {
        return $this->hasMany('\App\Education');
    }


    public function jobs()
    {
        return $this->hasMany('\App\Job');
    }


    public function families()
    {
        return $this->hasMany('\App\Family');
    }


    public function languages()
    {
        return $this->hasMany('\App\Language');
    }


    public function achievments()
    {
        return $this->hasMany('\App\Achievment');
    }
}
