<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $authUser = \Auth::user();

        $projects = Project::with(['pc','country'])->get();
                                    
        return view('projects.index')->with( ['projects' => $projects] );
    }
}
