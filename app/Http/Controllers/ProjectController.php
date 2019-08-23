<?php

namespace App\Http\Controllers;

use App\Project;
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
}
