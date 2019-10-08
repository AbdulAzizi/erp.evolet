<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title', 'admin_id'
    ];

    protected $appends = ['last_message'];

    public function participants()
    {
        return $this->belongsToMany('App\User','chat_participants');
    }
    
    public function admin()
    {
        return $this->belongsTo('App\User');
    }
    
    public function messages()
    {
        return $this->morphMany('App\Message','messageable');
    }

    public function getLastMessageAttribute()
    {
        return $this->messages->sortByDesc('created_at')->first();
    }
}
