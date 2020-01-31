<?php

namespace App\Http\Controllers;

use App\Division;
use App\Responsibility;
use App\User;
use App\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('responsibilities.descriptions')->get();

        $divisions = Division::with('positions.responsibilities.descriptions')->get();

        $authUser = \Auth::user();

        $currentRouteName = Route::currentRouteName();

        return view('positions', compact('positions', 'authUser', 'divisions', 'currentRouteName'));
    }

    public function show(Request $request, $id)
    {
        $user = User::with('positions.responsibilities.descriptions')->find($request->id);
        $division = new Division();

        if (\Auth::user()->positionLevel->name = "Руководитель" && \Auth::user()->division->id == $user->division->id)
            $division = Division::with('positions')->find($user->division_id);
        return view('profile.positions', compact('user', 'division'));
    }

    public function store(Request $request)
    {
        $position = Position::create([
            'name' => $request->position,
            'division_id' => $request->divisionId
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

}
