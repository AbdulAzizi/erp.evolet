<?php

namespace App\Http\Controllers;

use App\Country;
use App\Division;
use App\Project;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $authUser = \Auth::user();
        
        $projects = Project::whereHas('participants', function (Builder $query) use ($authUser) {
            $query->where('role_id', 4)
                  ->where('participant_id', $authUser->id);
        })->with(['pc', 'country'])->get();

        return view('projects.index')->with(['projects' => $projects]);
    }

    public function create()
    {
        $pcs = Division::promoCompanies();
        $users = User::with('division')->get();
        // return $users;
        $countries = Country::all();

        return view('projects.create', compact('pcs','users','countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'pc' => 'required',
            'kurator_pc' => 'required',
            'no' => 'required',
        ]);

        $project = Project::create([
            'pc_id' => $request->pc,
            'country_id' => $request->country,
        ]);

        $project->participants()->attach([
            $request->kurator_pc => ['role_id'=>4],
            $request->no => ['role_id'=>5],
        ]);

        return redirect()->route('projects.index');
    }
}
