<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use Filterable;

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
        'readed',
        'responsibility_description_id',
        'end_time',
        'start_date',
        'repeated',
        'repeat_id'
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
        return $this->belongsToMany('App\User', 'task_watchers')->withPivot('id');
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
        return $this->morphMany('App\History', 'historyable');
    }

    public function messages()
    {
        return $this->morphMany('App\Message', 'messageable');
    }

    public function forms()
    {
        return $this->belongsToMany('App\Form');
    }

    public function timeSets()
    {
        return $this->hasMany('App\Timeset');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    public function polls()
    {
        return $this->belongsToMany('App\Question')->withPivot('id');
    }

    public function questionTasks()
    {
        return $this->hasMany('App\QuestionTask');
    }

    public function responsibilityDescription()
    {
        return $this->belongsTo('App\ResponsibilityDescription');
    }

    public function setTimesetEndtime()
    {
        if (count($this->timeSets)) {
            if ($this->timeSets->last()->end_time == null) {
                $this->timeSets->last()->end_time = date(now());
            }
        }
    }

    public function attachments()
    {
        return $this->morphMany('App\Attachment', 'attachable');
    }

    public function repeat()
    {
        return $this->belongsTo('App\Repitition', 'repeat_id', 'id');
    }
}
