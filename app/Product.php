<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'process_id'
    ];

    public function fields()
    {
        return $this->belongsToMany('App\Field','product_values');
    }

    public function currentProcess()
    {
        return $this->belongsTo('App\Process','process_id');
    }
}
