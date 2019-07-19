<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    protected $table = "field_types";
    public $timestamps = false;

    public function fields()
    {
        return $this->hasMany(Field::class);
    }
    
}
