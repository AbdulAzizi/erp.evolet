<?php

namespace App\Http\Controllers;

use App\Division;
use App\Position;
use App\Responsibility;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $divisions = Division::with(['positions.responsibilities' => function ($query) {
            $query->orderBy('order')->with(['descriptions' => function ($query) {
                $query->orderBy('order');
            }]);
        }])->get()->toTree();

        return view('positions', compact('divisions'));
    }

    public function store(Request $request)
    {
        $position = Position::create([
            'name' => $request->position,
            'label' => $request->position,
            'division_id' => $request->divisionId,
        ]);

        $positionWithDescriptions = Position::with('responsibilities.descriptions')->find($position->id);

        return $positionWithDescriptions;
    }

    public function edit(Request $request)
    {
        $position = Position::find($request->id);

        $position->label = $request->label;

        $position->save();

        return $position;
    }

    public function delete(Request $request)
    {

        $position = Position::find($request->id);

        $position->responsibilities()->delete();

        $position->delete();
    }

    public function createResponsibility()
    {
        Responsibility::create([
            'position_id' => request('position_id'),
            'text' => request('text'),
        ]);

        return redirect()->back();
    }
    
    public function loadPositions()
    {
        $responsibilites = Position::all();

        return $responsibilites;
    }

    public function loadDivisionPositions(Request $request)
    {
        $divisionPositions = Position::where('division_id', $request->id)->get();

        return $divisionPositions;
    }

}
