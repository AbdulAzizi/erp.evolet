<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserTaskFilters extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function user_id($term)
    {
        $localUser = json_decode($term);

        return $this->builder->whereIn('responsible_id', $localUser);
    }

    public function priority($term)
    {
        return $this->builder->having('priority', $term);
    }

    public function status_id($term)
    {
        $localStatuses = json_decode($term);

        return $this->builder->whereIn('status_id', $localStatuses);
    }

    public function tag_id($term)
    {
        $localTags = json_decode($term);

        return $this->builder->whereHas('tags', function ($q) use ($localTags){
            $q->whereIn('tag_id', $localTags);
        });

    }
}
