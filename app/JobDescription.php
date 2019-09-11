<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobDescription extends Model
{

    public $timestamps = false;

    protected $fillable = ['responsibility_id', 'text'];

    public function responsibility()
    {
        return $this->belongsTo(Responsibility::class);
    }
}
