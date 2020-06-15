<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    public $guarded = [];
    public $timestamps = false;
    
    public function attachable()
    {
        return $this->morphTo();
    }
}
