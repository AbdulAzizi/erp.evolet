<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = ['resume_id', 'relation', 'birthday', 'name'];

    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo('\App\Resume');
    }
}
