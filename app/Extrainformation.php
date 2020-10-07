<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extrainformation extends Model
{
    protected $fillable = ['resume_id', 'type', 'description'];

    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo('\App\Resume');
    }
}
