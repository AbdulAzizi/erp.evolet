<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskFilters extends QueryFilters
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function from_id($term)
    {
        return $this->builder->where([
            'from_id' => $term,
            'from_type' => "App\User"
        ]);
    }

    public function responsible_id($term)
    {
        return $this->builder->where([
            'responsible_id' => $term
        ]);
    }

    public function watcher_id($term)
    {
        return $this->builder->whereHas('watchers', function($q) use ($term) {
            $q->where('user_id', $term);
        });
    }
}
