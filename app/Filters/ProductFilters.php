<?php

namespace App\Filters;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Division;
use App\Country;

class ProductFilters extends QueryFilters{
    
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function pc($term)
    {
        return $this->builder->where('pc_id', Division::where('name', 'LIKE' ,"%$term%")->first()->id );
    }

    public function pc_ids($term)
    {
        $localTerm = json_decode($term);

        return $this->builder->whereHas('project', function (Builder $query) use ($localTerm) {
            $query->whereIn('pc_id', $localTerm);
        });
    }

    public function pc_id($term)
    {
        return $this->builder->whereHas('project', function (Builder $query) use ($term) {
            $query->where('pc_id', $term);
        });
    }

    public function country($term)
    {
        return $this->builder->where('country_id', Country::where('name', 'LIKE' ,"%$term%")->first()->id );
    }
    
    public function country_ids($term)
    {
        $localTerm = json_decode($term);

        return $this->builder->whereHas('project', function (Builder $query) use ($localTerm) {
            $query->whereIn('country_id', $localTerm);
        });
    }

    public function country_id($term)
    {
        return $this->builder->whereHas('project', function (Builder $query) use ($term) {
            $query->where('country_id', $term);
        });
    }

    public function project_id($term)
    {
        return $this->builder->whereHas('project', function (Builder $query) use ($term) {
            $query->where('id', $term);
        });
    }

    public function product_id($term)
    {
        return $this->builder->where('id', $term);
    }
}