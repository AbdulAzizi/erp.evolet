<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Field extends Model
{
    public $timestamps = false;

    protected $with = ['type'];

    public $guarded = [];

    public function forms()
    {
        return $this->belongsToMany(Form::class);
    }

    public function fields()
    {
        return $this->belongsToMany(Product::class, 'product_values')->using('App\ProductValue');
    }

    public function type()
    {
        return $this->belongsTo(FieldType::class, 'type_id');
    }

}
