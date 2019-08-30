<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'planned_time',
        'deadline',
        'responsible_id',
        'from_id',
        'from_type',
        'created_at',
    ];

    // protected $with = ['responsible.user','from'];

    public function from()
    {
        return $this->morphTo();
    }

    public function responsible()
    {
        return $this->belongsTo('App\User', 'responsible_id');
    }

    public function watchers()
    {
        return $this->belongsToMany('App\User', 'task_watchers');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function history()
    {
        return $this->morphMany(History::class, 'happened_with');
    }

    public function polls()
    {
        return $this->belongsToMany('App\Question');
    }
}
