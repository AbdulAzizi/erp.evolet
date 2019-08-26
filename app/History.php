<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $timestamps = false;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function previous()
    {
        return $this->hasOne(History::class, 'previous_id', 'id');
    }

    public function next() 
    {
        return $this->hasOne(History::class, 'id', 'previous_id');
    }

    public function happendWith()
    {
        return $this->morphTo();
    }
}
