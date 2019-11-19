<?php

namespace App\Filters;

use App\Country;
use App\Division;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilters extends QueryFilters
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function fields($term)
    {
        $fields = json_decode($term);
        foreach ($fields as $field) {
            $field = json_decode($field);
            $key = key($field);
            $value = current($field);
            if (is_numeric($value)) {
                $this->builder->whereHas('values', function (Builder $query) use ($value, $key) {
                    $query->where('field_id', $key)
                        ->where('value', $value);
                });
            } else {
                $this->builder->whereHas('values', function (Builder $query) use ($value, $key) {
                    $query->where('field_id', $key)
                        ->where('value', 'LIKE', "%$value%");
                });
            }
        }
        return $this->builder;
        // dd($localTerm);
    }
    public function process_id($term)
    {
        return $this->builder->where('process_id', $term);
    }

    public function pc($term)
    {
        return $this->builder->where('pc_id', Division::where('name', 'LIKE', "%$term%")->first()->id);
    }

    public function pc_id($term)
    {
        return $this->builder->whereHas('project', function (Builder $query) use ($term) {
            $query->where('pc_id', $term);
        });
    }

    public function pc_ids($term)
    {
        $localTerm = json_decode($term);

        return $this->builder->whereHas('project', function (Builder $query) use ($localTerm) {
            $query->whereIn('pc_id', $localTerm);
        });
    }

    public function country($term)
    {
        return $this->builder->where('country_id', Country::where('name', 'LIKE', "%$term%")->first()->id);
    }

    public function country_id($term)
    {
        return $this->builder->whereHas('project', function (Builder $query) use ($term) {
            $query->where('country_id', $term);
        });
    }

    public function country_ids($term)
    {
        $localTerm = json_decode($term);

        return $this->builder->whereHas('project', function (Builder $query) use ($localTerm) {
            $query->whereIn('country_id', $localTerm);
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
