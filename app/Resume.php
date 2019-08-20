<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{

    protected $fillable = ['name', 'birthday', 'user_id', 'school', 'university', 'marital_status', 'childs'];

    public function user()
    {
        return $this->belongsTo('\App\User');
    }
}
