<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $with = ['user'];

    public $timestamps = false;

    public function division()
    {
        return $this->belongsTo('App\Division');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function responsibility()
    {
        return $this->belongsTo('App\Responsibility');
    }

    public function givenTasks()
    {
        return $this->morphMany('App\Task', 'from');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'responsible_id');
    }

    public function allTasks()
    {
        return collect([
            [
                'name' => 'Мои Задачи',
                'data' => $this->tasks->load(['responsible.user', 'from'])
            ],
            [
                'name' => 'Порученные',
                'data' => $this->givenTasks->load(['responsible.user', 'from']),
            ],
        ]);
    }
}
