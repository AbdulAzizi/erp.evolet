<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Manager extends Pivot
{
    protected $table = 'managers';

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
    public function pc()
    {
        return $this->belongsTo('App\Division', 'pc_id');
    }
    public function manager()
    {
        return $this->belongsTo('App\User', 'manager_id');
    }
    public function no()
    {
        return $this->belongsTo('App\User', 'no_id');
    }
    public function pcRepresentative()
    {
        return $this->belongsTo('App\User', 'pc_representative_id');
    }
}
