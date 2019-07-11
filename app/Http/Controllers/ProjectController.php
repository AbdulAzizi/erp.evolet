<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $authUser = \Auth::user();

        $projects =  \App\Manager::where('manager_id', $authUser->id)
                                    ->with(['manager','country','pc', 'no','pcRepresentative'])
                                    ->get();
                                    
        return view('projects.index')->with( ['projects' => $projects] );
    }
}
