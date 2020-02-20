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

        $division = Division::with('head', 'users', 'positions.responsibilities.descriptions', 'tags')->withDepth()->descendantsAndSelf($userDivisionId)->toTree()->first();
        
        $divisions = Division::with('positions.responsibilities.descriptions')->withDepth()->get();

        $positionLevels = PositionLevel::where('name', '!=', 'Руководитель')->get();

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
            'authUser', 
            'divisions'
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
}
