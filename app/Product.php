<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable;
    
    public $timestamps = false;

    protected $fillable = [
        'process_id',
        'project_id'
    ];

    public function fields()
    {
        return $this->belongsToMany('App\Field','product_values')->using('App\ProductValue')->withPivot('value');
    }

    public function fieldsWithLists()
    {
        return $this->belongsToMany(FormField::class, 'product_values',  'product_id', 'field_id')->withPivot('value');
    }

    public function currentProcess()
    {
        return $this->belongsTo('App\Process','process_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function history()
    {
        return $this->morphMany('App\History', 'historyable');
    }
    
    public function messages()
    {
        return $this->morphMany('App\Message','messageable');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task');
    }

    public function processes()
    {
        return $this->belongsToMany('App\Process')->withPivot('spent_time')->withTimestamps();
    }
}
