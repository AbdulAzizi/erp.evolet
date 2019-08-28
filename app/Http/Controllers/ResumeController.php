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

class ResumeController extends Controller
{

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

    public function create(Request $request)
    {

        Resume::create([

            'birthday' => $request->birthday,
            'male_female' => $request->gender,
            'phone' => $request->phone,
            'military_status' => $request->military_status,
            'user_id' => auth()->id()

        ]);

        return redirect()->back();

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
}
