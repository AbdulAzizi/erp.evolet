<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HumanResourcesController extends Controller
{
    public function index()
    {
        return view('humanResources.index');
    }
}
