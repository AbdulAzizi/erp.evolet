<?php

namespace App\Http\Controllers;

use App\Division;
use App\Position;
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
        $division = Division::with('head', 'users', 'responsibilities.descriptions')->withDepth()->descendantsAndSelf($userDivisionId)->toTree()->first();

        $positions = Position::where('name', '!=', 'Руководитель')->get();
        $authUser = \Auth::user();

        if ($division->head) {
            $isUserHead = $division->head->id === $authUser->id;
        } else {
            $isUserHead = false;
        }

        $isUserHead = json_encode($isUserHead);
        $oldInputs = json_encode(Session::getOldInput());
        $jsonPositions = json_encode($positions);
        $jsonResponsibilities = json_encode($division->responsibilities);

        return view('division', compact('division', 'positions', 'isUserHead', 'oldInputs', 'jsonPositions', 'jsonResponsibilities'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'parentDivisionId' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $parentDivision = Division::find($request->input('parentDivisionId'));

        Division::create($request->only(['name', 'abbreviation']), $parentDivision);

        return redirect()->back();
    }
}
