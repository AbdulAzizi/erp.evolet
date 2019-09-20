<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function descriptions()
    {
        return $this->hasMany(JobDescription::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
