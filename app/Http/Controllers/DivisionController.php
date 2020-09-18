<?php

namespace App\Http\Controllers;

use App\Division;
use App\PositionLevel;
use App\User;
use Illuminate\Http\Request;
use Session;
use Validator;

class DivisionController extends Controller
{
    public function create(Request $request)
    {
        $parentDivision = Division::where('name', 'Промо Компания')->get();

        return Division::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
        ], $parentDivision->first());
    }

    public function show()
    {
        $userDivisionId = auth()->user()->division_id;

        $division = Division::with([
            'head', 'users', 'tags',
            'positions.responsibilities' => function ($query) {
                $query->orderBy('order')->with(['descriptions' => function ($query) {
                    $query->orderBy('order');
                }]);
            }
        ])
            ->withDepth()
            ->descendantsAndSelf($userDivisionId)
            ->toTree()
            ->first();

        $positionLevels = PositionLevel::where('name', '!=', 'Руководитель')
            ->get();

        $authUser = \Auth::user();

        if ($division->head) {
            $isUserHead = $division->head->id === $authUser->id;
        } else {
            $isUserHead = false;
        }

        $isUserHead = json_encode($isUserHead);
        $oldInputs = json_encode(Session::getOldInput());
        $jsonPositionLevels = json_encode($positionLevels);
        $jsonPositions = json_encode($division->positions);

        return view('division', compact(
            'division',
            'positionLevels',
            'isUserHead',
            'oldInputs',
            'jsonPositionLevels',
            'jsonPositions',
            'authUser'
        ));
    }

    public function store(Request $request)
    {

        $division = Division::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'parent_id' => $request->parent_id
        ]);

        $divisionWithRelations = Division::with('users', 'head', 'children')->withDepth()->find($division->id);

        return $divisionWithRelations;
    }

    public function delete(Request $request)
    {
        $division = Division::find($request->id);

        $division->children()->delete();

        $division->delete();
    }

    public function loadDivisions()
    {
        $divisions = Division::all();

        return $divisions;
    }

    public function tags(Request $request)
    {
        $division = Division::with('tags')->find($request->id);

        return $division->tags;
    }

    public function edit(Request $request)
    {
        $division = Division::find($request->id);

        foreach ($request->data as $data) {
            $division->update([
                $data['name'] => $data['value']
            ]);
        }

        return $division;
    }

    public function getUsers()
    {
        $users = [];

        $authUserId = auth()->id();

        $division = Division::where('head_id', $authUserId)->first();
        
        if ($division) {

            $divisionWithDepth = $division->withDepth()->descendantsAndSelf($division->id);

            foreach ($divisionWithDepth as $division) {
                foreach ($division->users as $user) {
                    if ($user->id != $authUserId) {
                        $users[] = $user;
                    }
                }
            }

            return $users;
        }
    }

    public function getDivisions(Request $request)
    {
        $query = Division::query();

        if($request->users){
            $query->with('users');
        }
        if($request->has_users){
            $query->whereHas('users');
        }

        return $query->get();
    }
}
