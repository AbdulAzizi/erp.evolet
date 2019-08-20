<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model

{
    protected $fillable = ['degree', 'start_at', 'end_at', 'specialty', 'name', 'resume_id'];

    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo('\App\Resume');
    }
}
