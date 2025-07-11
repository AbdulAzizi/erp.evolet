<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tether extends Model
{
    public $timestamps = false;

    public $guarded = [];
    
    public function fromProcess()
    {
        return $this->belongsTo('App\Process', 'from_process_id');
    }

    public function toProcess()
    {
        return $this->belongsTo('App\Process', 'to_process_id');
    }

    public function forms()
    {
        return $this->belongsToMany('App\Form');
    }
}
