<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{

    public $timestamps = false;

    protected $fillable = ['position_id', 'text', 'order'];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function descriptions()
    {
        return $this->hasMany('App\ResponsibilityDescription');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
