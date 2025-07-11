<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'pc_id',
        'country_id',
    ];

    public function pc()
    {
        return $this->belongsTo('App\Division');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function participants()
    {
        return $this->belongsToMany('App\User','project_participants','project_id','participant_id')
                        ->using('App\ProjectParticipant')
                        ->withPivot([
                            'role_id'
                        ]);;
    }

    public function projectParticipant()
    {
        return $this->hasMany('App\ProjectParticipant', 'project_id', 'id');
    }
    

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
