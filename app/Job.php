<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['position', 'start_at', 'end_at', 'location', 'resume_id', 'company_name'];

    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo('\App\Resume');
    }
}
