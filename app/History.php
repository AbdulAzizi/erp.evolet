<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function historyable()
    {
        return $this->morphTo();
    }
}
