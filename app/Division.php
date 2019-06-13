<?php

namespace App;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;


class Division extends Model
{
    use NodeTrait;

    public $timestamps = false;

    protected $fillable = ['name', 'abbreviation', 'head_id'];
    
    public function head()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
