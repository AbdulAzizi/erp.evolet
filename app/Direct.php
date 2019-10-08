<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direct extends Model
{
    public $timestamps = false;
    protected $fillable = ['from','to'];
    protected $appends = ['last_message'];

    public function messages()
    {
        return $this->morphMany('App\Message','messageable');
    }

    public function to()
    {
        return $this->belongsTo('App\User','to');
    }

    public function from()
    {
        return $this->belongsTo('App\User','from');
    }

    public function getLastMessageAttribute()
    {
        return $this->messages->sortByDesc('created_at')->first();
    }
}
