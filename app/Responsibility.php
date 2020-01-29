<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{

    public $timestamps = false;

    protected $fillable = ['position_id', 'text'];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function descriptions()
    {
        return $this->hasMany('App\ResponsibilityDescription');
    }
}
