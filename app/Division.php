<?php

namespace App;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;


class Division extends Model
{
    use NodeTrait;

    public $timestamps = false;

    protected $fillable = ['name', 'abbreviation', 'head_id'];
    
    public function head()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function managers()
    {
        return $this->belongsToMany('App\User','managers','pc_id','manager_id')->as('manager')->withPivot('pc_id','country_id');
    }

    public function countries()
    {
        return $this->belongsToMany('App\Country','managers','pc_id','manager_id');
    }
}
