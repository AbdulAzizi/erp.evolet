<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        return redirect('/tasks?all=true');
    }
    
    public function company()
    {
        $company = \App\Division::get()->toTree();
        
        return view('company',compact('company'));
    }
}
