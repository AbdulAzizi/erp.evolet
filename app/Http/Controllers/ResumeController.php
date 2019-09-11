<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Resume;
use App\Education;
use App\Job;
use App\Family;
use App\Language;
use App\Achievment;
use PDF;

class ResumeController extends Controller
{


    public function index()
    {
        $resumes = Resume::where('creator', auth()->id())->with(['educations', 'languages'])->doesnthave('owner')->get();

        return view('resume.index', compact('resumes'));
    }
    public function show(Request $request)
    {
        $user = User::with(
            'resume.educations',
            'resume.jobs',
            'resume.families',
            'resume.achievments',
            'resume.languages'
        )->find($request->id);


        return view('profile.curriculum', compact('user'));


    }

    public function showSingle(Request $request)
    {
        $user = auth()->user();


        $resume = Resume::with(
            'educations',
            'jobs',
            'families',
            'achievments',
            'languages'
        )->find($request->id);

        return view('resume.show', compact('resume', 'user'));


    }

    public function headResumes(Request $request)
    {
        $resumes = Resume::with(['educations', 'languages'])->doesnthave('owner')->get();

        return view('resume.headResumes', compact('resumes'));
    }

    public function create(Request $request)
    {
        if($request->own == 'false'){
            $resume = Resume::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'birthday' => $request->birthday,
            'male_female' => $request->gender,
            'phone' => $request->phone,
            'email' => $request->email,
            'creator' => auth()->id()
            ]);

            return redirect('/resume/'.$resume->id);
        }
        else{

            $user = User::find(auth()->id());

            $resume = Resume::create([
                'name' => $user->name,
                'surname' => $user->surname,
                'birthday' => $request->birthday,
                'male_female' => $request->gender,
                'phone' => $request->phone,
                'email' => $user->email,
                'creator' => auth()->id()
            ]);


            $resume->owner()->attach($user->id);

            return redirect('/users/' . auth()->id() . '/cv');

        }




    }

    public function educationAdd()
    {
        return Education::create([
            'degree' => request('degree'),
            'name' => request('name'),
            'start_at' => request('start_at'),
            'end_at' => request('end_at'),
            'specialty' => request('specialty'),
            'resume_id' => request('resume_id')
        ]);
    }

    public function educationDelete(Request $request)
    {
        Education::find($request->id)->delete();

        return "success";
    }

    public function educationEdit(Request $request)
    {
         $education = Education::find($request->id);

         $education->update([

            'degree' => $request->degree,
            'name' => $request->institute,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'specialty' => $request->specialty

        ]);

        return $education;
    }

    public function jobAdd()
    {
        return Job::create([
            'company_name' => request('company_name'),
            'start_at' => request('start_at'),
            'end_at' => request('end_at'),
            'position' => request('position'),
            'location' => request('location'),
            'resume_id' => request('resume_id')
        ]);

    }

    public function jobDelete(Request $request)
    {
        Job::find($request->id)->delete();

        return "success";
    }

    public function familyAdd(Request $request)
    {
        return Family::create([
            'relation' => $request->relation,
            'birthday' => $request->birthday,
            'name' => $request->name,
            'resume_id' => $request->resume_id
        ]);
    }
    public function familyDelete(Request $request)
    {
        Family::find($request->id)->delete();

        return 'success';
    }

    public function languageAdd(Request $request)
    {
        return Language::create([
            'name' => $request->name,
            'level' => $request->level,
            'resume_id' => $request->resume_id
        ]);
    }
    public function languageDelete(Request $request)
    {
        Language::find($request->id)->delete();

        return 'success';
    }

    public function achievmentAdd(Request $request)
    {
        return Achievment::create([
            'type' => $request->type,
            'description' => $request->description,
            'resume_id' => $request->resume_id
        ]);
    }
    public function achievmentDelete(Request $request)
    {
        Achievment::find($request->id)->delete();

        return 'success';
    }

    public function pdf(Request $request)
    {
        $resume = Resume::find($request->id);

        $pdf = PDF::loadView('resume.export_pdf', compact('resume'));

        return $pdf->download($resume->name . '.pdf');
    }
}
