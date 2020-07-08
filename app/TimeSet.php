<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Timeset extends Model
{
    use Filterable;
    
    public $timestamps = false;

    protected $fillable = ['task_id', 'start_time', 'end_time'];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

}
