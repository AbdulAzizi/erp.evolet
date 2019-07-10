<?php

namespace App\Http\Controllers;

use App\Division;
use App\Position;
use App\Responsibility;
use Illuminate\Http\Request;
use Validator;

class DivisionController extends Controller
{
    public function show()
    {
        $userDivisionId = auth()->user()->division_id;

        $division = Division::with('head', 'users')->withDepth()->descendantsAndSelf($userDivisionId)->toTree();
            
        $responsibilities = Responsibility::where('name', '!=', 'Директор')->get();
        $positions = Position::where('name', '!=', 'Руководитель')->get();

        return view('division', compact('division', 'responsibilities', 'positions'));
    }

    public function store(Request $request)
    {
        dd($request->all()); 
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'parentDivisionId' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $parentDivision = Division::find($request->input('parentDivisionId'));

        Division::create($request->only(['name', 'abbreviation']), $parentDivision);

        return redirect()->back();
    }
}
