<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $timestamps = false;

    public $guarded = [];
    
    public function fields()
    {
        return $this->belongsToMany('App\Field');
    }
}
