<?php

namespace App\Http\Controllers;

use App\Division;
use App\Responsibility;
use App\User;
use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('responsibilities.descriptions')->get();
        return view('responsibilites', compact('positions'));
    }

    public function show(Request $request, $id)
    {
        $user = User::with('positions.responsibilities.descriptions',)->find($request->id);
        $division = new Division();

        if (\Auth::user()->positionLevel->name = "Руководитель" && \Auth::user()->division->id == $user->division->id)
            $division = Division::with('positions')->find($user->division_id);
        return view('profile.positions', compact('user', 'division'));
    }

    public function store(Request $request)
    {
        $position = Position::create([
            'name' => $request->position,
            'division_id' => $request->id
        ]);

        $positionWithDescriptions = Position::with('responsibilities.descriptions')->find($position->id);

        return $positionWithDescriptions;
    }

    public function edit(Request $request)
    {
        $position = Position::find($request->id);

        $position->name = $request->name;

        $position->save();

        return $position;
    }

    public function delete(Request $request){

        $position = Position::find($request->id);

        $position->responsibilities()->delete();

        $position->delete();
    }
    
    public function createResponsibility()
    {
        Responsibility::create([
            'position_id' => request('position_id'),
            'text' => request('text')
        ]);

        return  redirect()->back();
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
