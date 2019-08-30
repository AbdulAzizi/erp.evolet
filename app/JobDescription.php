<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobDescription extends Model
{

    public $timestamps = false;

    public function responsibility()
    {
        return $this->belongsTo(Responsibility::class);
    }
}
