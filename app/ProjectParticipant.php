<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectParticipant extends Pivot
{
    public $with = [ 'role' ];

    protected $table = 'project_participants';

    public function role()
    {
        return $this->belongsTo('App\Responsibility');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function participant()
    {
        return $this->belongsTo('App\User','participant_id');
    }
    
}
