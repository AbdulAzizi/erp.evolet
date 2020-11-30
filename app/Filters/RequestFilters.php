<?php

namespace App\Filters;

use App\Division;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RequestFilters extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function isHead()
    {
        $ceo = collect(auth()->user()->positions)->filter(function ($elem) {
            return $elem->name == "РВЗ" || $elem->name == "HR";
        });

        $headOfDivision = auth()->user()->division->head_id == auth()->user()->id;

        if(count($ceo) && $headOfDivision || count($ceo))
        {
            return $this->builder->where('user_id', '!=', auth()->user()->id)->where('status', '>=', 1);
        }
        else if($headOfDivision && !count($ceo)) 
        {
            $users = [];
    
            $division = Division::find(auth()->user()->division_id);
    
            foreach ($division->users as $user) {
                $users[] = $user->id;
            }
    
            return $this->builder->whereIn('user_id', $users)->where('user_id', "!=", auth()->user()->id);
        }
    }

    public function isUser()
    {
        return $this->builder->where('user_id', auth()->user()->id);
    }

    public function id($term)
    {
        return $this->isHead()->whereHas("user", function($q) use ($term){
            $q->having('id', $term);
        });
    }

    public function defineRole()
    {
        return $this->isUser() ? $this->isUser() : $this->isHead();
    }
}
