<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'body', 'messageable_id', 'messageable_type'];

    protected $with = ['sender'];

    public function messageable()
    {
        return $this->morphTo();
    }

    public function sender()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
