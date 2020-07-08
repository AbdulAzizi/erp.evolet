<?php

namespace App\Http\Controllers;

use App\Division;
use App\Filters\TimesetFilters;
use App\Timeset;
use App\User;
use Illuminate\Http\Request;

class TimesetController extends Controller
{
    public function index()
    {
        $divisions = collect([]);

        if ($this->isExeption()) {
            $divisions = Division::whereHas('users')->get();
            $users = User::all();
        } else {
            // get divisions of auth
            $divisionsIDs = Division::descendantsAndSelf(auth()->user()->division_id)->pluck('id');
            // get users of those divisions
            $users = User::whereIn('division_id', $divisionsIDs)->get();
        }

        return view('timesets.index', compact('divisions', 'users'));
    }

    public function getTimesets(Request $request, TimesetFilters $filters)
    {
        $timesets = Timeset::filter($filters)
            ->with('task')
            ->get();

        if ($request->has('division_id')) {
            $users = TimesetFilters::getUsers();
        } else {
            $users = User::alone()->get();
        }
        return compact('users', 'timesets');
    }

    private function isExeption()
    {
        // Get exeptions
        $exeptions = Division::whereIn('abbreviation', ['ОРПО', 'ОУПС', 'Evolet'])->get()->pluck('id');
        // Get auth user
        $auth = auth()->user();

        return $exeptions->contains($auth->division_id);
    }
}
