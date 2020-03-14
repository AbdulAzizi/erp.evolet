<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskFilters extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function from_id($term)
    {
        return $this->builder->where([
            'from_id' => $term,
            'from_type' => "App\User",
        ]);
    }

    public function responsible_id($term)
    {
        return $this->builder->where('responsible_id', $term);
    }

    public function watcher_id($term)
    {
        return $this->builder->whereHas('watchers', function ($q) use ($term) {
            $q->where('user_id', $term);
        });
    }

    public function all($term)
    {
        $authUser = \Auth::user();

        return $this->builder->where(function ($q) use ($authUser){
            $q->where('from_id', $authUser->id)
              ->orWhere('responsible_id', $authUser->id)
              ->orWhereHas('watchers', function ($watcher) use ($authUser) {
                  $watcher->where('user_id', $authUser->id);
              });
        });
    }

    public function priority($term)
    {   

        return $this->builder->having('priority', $term);

    }

    public function tags($term)
    {
        $localTerm = json_decode($term);

        return $this->builder->whereHas('tags', function ($q) use ($localTerm){
            $q->whereIn('tag_id', $localTerm);
        });

    }

    public function title($term)
    {
        return $this->builder->whereHas("responsibilityDescription", function ($q) use ($term) {
            $q->where("title", "LIKE", "%$term%");
        });
    }
}
