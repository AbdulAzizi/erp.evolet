<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductValue extends Pivot
{
    protected $table = 'product_values';
    
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function field()
    {
        return $this->belongsTo('App\Field');
    }
}
