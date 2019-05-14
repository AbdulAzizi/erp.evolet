<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function from()
    {
        return $this->morphTo();
    }
}
