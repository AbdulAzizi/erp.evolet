<?php

namespace App\Http\Controllers;

use App\Division;
use App\JobDescription;
use App\User;
use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('descriptions')->get();
        return view('responsibilites', compact('positions'));
    }

    public function show(Request $request, $id)
    {
        $user = User::with('positions.descriptions')->find($request->id);
        $division = new Division();

        if (\Auth::user()->positionLevel->name = "Руководитель" && \Auth::user()->division->id == $user->division->id)
            $division = Division::with('positions')->find($user->division_id);
        return view('profile.positions', compact('user', 'division'));
    }

    public function store(Request $request)
    {
        $data = [];

        $position = Position::create([
            'name' => $request->name,
            'division_id' => $request->id
        ]);


        foreach ($request->descriptions as $description) {
            if ($description['level'] !== null && $description['text'] !== null) {
                $data[] = [
                    'position_id' => $position->id,
                    'text' => $description['text'],
                    'level' => $description['level'],
                    'planned_time' => $description['days'] * 86400000 + $description['hours'] * 3600000 + $description['minutes'] * 60000
                ];
            }
        }
        $jobDescription = JobDescription::insert($data);

        $positionWithDescriptions = Position::with('descriptions')->find($position->id);

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

        $position->descriptions()->delete();

        $position->delete();
    }
    
    public function createJobDescription()
    {
        JobDescription::create([
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

    public function addPosition(Request $request)
    {
        $data = [];

        foreach ($request->descriptions as $description) {
            if ($description['level'] !== null && $description['text'] !== null) {
                $data[] = [
                    'position_id' => $request->id,
                    'text' => $description['text'],
                    'level' => $description['level'],
                    'planned_time' => $description['days'] * 86400000 + $description['hours'] * 3600000 + $description['minutes'] * 60000
                ];
            }
        }

        $jobdescription = JobDescription::insert($data);

        $position = Position::with('descriptions')->find($request->id);

        return $position;
    }
}
