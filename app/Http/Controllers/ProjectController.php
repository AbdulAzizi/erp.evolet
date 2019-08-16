<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $authUser = \Auth::user();
        // return $request->sortBy;
        if($request->sortBy){
            $projects = Project::has('pc')
                        ->whereHas('participants', function (Builder $query) use ($authUser) {
                            $query->where('role_id', 4)
                                ->where('participant_id',$authUser->id);
                        })
                        ->with(['country','products.fields','pc'])
                        ->get()
                        ->groupBy($request->sortBy.'.name');
        } else{
            $projects = Project::with(['pc','country'])->get();
        }

                                    
        return view('projects.index')->with( ['projects' => $projects] );
    }
}
