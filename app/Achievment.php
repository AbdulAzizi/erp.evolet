<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achievment extends Model
{
    protected $fillable = ['resume_id', 'type', 'description', 'date'];

    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo('\App\Resume');
    }
}
