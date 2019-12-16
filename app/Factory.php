<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    protected $fillable = ['name','about','country_id','stability_zone','website','product_class','logo'];

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
