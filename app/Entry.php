<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'date', 'sign_in', 'sign_out',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
