<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable;
    
    public $timestamps = false;

    protected $fillable = [
        'process_id'
    ];

    public function fields()
    {
        return $this->belongsToMany('App\Field','product_values')->withPivot('value');
    }

    public function currentProcess()
    {
        return $this->belongsTo('App\Process','process_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

}
