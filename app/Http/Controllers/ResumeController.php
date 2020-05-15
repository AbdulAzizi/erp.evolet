<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Resume;
use PDF;
class ResumeController extends Controller
{
    // Show all resumes
    public function index()
    {
        $resumes = Resume::where('creator', auth()->id())->with(['educations', 'languages'])->doesnthave('owner')->get();

        return view('resume.index', compact('resumes'));
    }

    // Show resume in users profile
    public function show(Request $request)
    {
        $user = User::with(
            'resume.educations',
            'resume.jobs',
            'resume.families',
            'resume.achievments',
            'resume.languages',
            'resume.skills'
        )->find($request->id);

        return view('profile.curriculum', compact('user'));
    }

    public function edit(Request $request, $id)
    {
        $resume = Resume::find($id);

        $resume->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'male_female' => $request->gender
        ]);

        $resume->save();

        return $resume;
    }

    public function create(Request $request)
    {
        $gender = json_decode($request->gender);

        if ($request->own == 'false') {
            $resume = Resume::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'birthday' => $request->birthday,
                'male_female' => $gender,
                'phone' => $request->phone,
                'email' => $request->email,
                'creator' => auth()->id()
            ]);

            return redirect('/resume/' . $resume->id);

        } else {

            $user = User::find(auth()->id());

            $resume = Resume::create([
                'name' => $user->name,
                'surname' => $user->surname,
                'birthday' => $request->birthday,
                'male_female' => $gender,
                'phone' => $request->phone,
                'email' => $user->email,
                'creator' => auth()->id()
            ]);

            $resume->owner()->attach($user->id);

            return redirect('/users/' . auth()->id() . '/cv');
        }
    }

    // Show candidate resume added by employee
    public function showSingle(Request $request)
    {
        $user = auth()->user();

        $resume = Resume::with(
            'educations',
            'jobs',
            'families',
            'achievments',
            'languages',
            'skills'
        )->find($request->id);

        return view('resume.show', compact('resume', 'user'));
    }

    // Show resumes to HR
    public function hrShowResumes(Request $request)
    {
        $resumes = Resume::with(['educations', 'languages'])->doesnthave('owner')->get();

        return view('resume.headResumes', compact('resumes'));
    }

    public function pdf(Request $request)
    {
        $resume = Resume::find($request->id);

        $pdf = PDF::loadView('resume.export_pdf', compact('resume'));

        return $pdf->download($resume->name . '.pdf');
    }
}
