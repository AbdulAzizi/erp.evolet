<?php

namespace App\Filters;

use App\Division;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use \App\Request as UserRequest;

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
            return $elem->name == "РВЗ" || $elem->name == "ОУПС";
        });

        $headOfDivision = auth()->user()->division->head_id == auth()->user()->id;

        if($headOfDivision) 
        {
            $users = [];
    
            $division = Division::find(auth()->user()->division_id);
    
            foreach ($division->users as $user) {
                $users[] = $user->id;
            }
    
            return $this->builder->whereIn('user_id', $users)->where('user_id', "!=", auth()->user()->id);
        }
        else if($ceo)
        {
            return $this->builder->where('user_id', '!=', auth()->user()->id)->where('status', '>=', 1);
        }
    }

    public function isUser()
    {
        return $this->builder->where('user_id', auth()->user()->id);
    }

    public function type($term)
    {
        return $this->defineRole()->having('type', $term);
    }

    public function defineRole()
    {
        return $this->isUser() ? $this->isUser() : $this->isHead();
    }
}
