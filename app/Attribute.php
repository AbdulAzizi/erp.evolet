<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function facility() {
        return $this->belongsTo('App\Facility');
    }
}
