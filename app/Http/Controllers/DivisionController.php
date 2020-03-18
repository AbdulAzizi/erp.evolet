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
                            'head', 'users','tags',
                            'positions.responsibilities' => function ($query) {
                                $query->orderBy('order')->with(['descriptions' => function ($query) {
                                    $query->orderBy('order');
                                }]);
                            }])
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

    public function edit(Request $request){
        $division = Division::find($request->id);

        foreach($request->data as $data){
            $division->update([
               $data['name'] => $data['value'] 
            ]);
        }

        return $division;
    }

    public function getUsers(Request $request){

        $users = [];

        $division = Division::where('head_id', $request->id)->first();
        
        $divisionWithDepth = $division->withDepth()->descendantsOf($division->id);
        
        foreach($divisionWithDepth as $division){
            foreach($division->users as $user){
                $users[] = $user;
            }
        }

        return $users;
    }
}
