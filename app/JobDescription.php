<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobDescription extends Model
{

    public $timestamps = false;

    protected $fillable = ['position_id', 'text'];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
