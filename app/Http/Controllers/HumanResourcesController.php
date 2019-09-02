<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Resume;

class HumanResourcesController extends Controller
{
    public function index()
    {
        $amountOfUsers = User::all()->count();

        $amountOfResumes = Resume::all()->count();

        return view('humanResources.index', compact('amountOfUsers', 'amountOfResumes'));
    }

    public function showResumes()
    {
        $resumes = Resume::with(['educations', 'languages', 'owner'])->get();

        return view('humanResources.resumes', compact('resumes'));
    }
}
