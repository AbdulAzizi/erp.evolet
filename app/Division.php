<?php

namespace App;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;


class Division extends Model
{
    use NodeTrait;

    public $timestamps = false;

    protected $fillable = ['name', 'abbreviation', 'head_id', 'parent_id'];

    public function head()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function projects()
    {
        return $this->hasMany('App\Project', 'pc_id');
    }

    public function countries()
    {
        return $this->belongsToMany('App\Country', 'managers', 'pc_id', 'manager_id');
    }

    public static function promoCompanies()
    {
        return self::withDepth()->having('depth', '=', 4)->get();
    }

    public function positions()
    {
        return $this->hasMany('App\Position');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
}
