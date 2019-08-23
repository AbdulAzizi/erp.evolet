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

    public function store(Request $request)
    {

        $degrees = json_decode($request->degrees);
        $jobs = json_decode($request->jobs);
        $families = json_decode($request->families);
        $languages = json_decode($request->languages);
        $achievments = json_decode($request->achievments);

        Resume::create([

            'birthday' => $request->birthday,
            'male_female' => $request->gender,
            'phone' => $request->phone,
            'military_status' => $request->military_status,
            'user_id' => auth()->id()

        ]);

        $resume = Resume::where('user_id', auth()->id())->first()->id;

        $degreesToInsert = [];


        foreach($degrees as $degree){

            $degreesToInsert[] = [

                'degree' => $degree->degree,
                'start_at' => $degree->start_at,
                'end_at' => $degree->end_at,
                'name' => $degree->institute,
                'specialty' => $degree->specialty,
                'resume_id' => $resume

            ];
        }

        Education::insert($degreesToInsert);

        $jobsToInsert = [];

        foreach($jobs as $job){

            $jobsToInsert[] = [

                'start_at' => $job->start_at,
                'end_at' => $job->end_at,
                'company_name' => $job->name,
                'position' => $job->position,
                'location' => $job->location,
                'resume_id' => $resume

            ];

        }

        Job::insert($jobsToInsert);

        $familiesToInsert = [];

        foreach($families as $family){

            $familiesToInsert[] = [

                'relation' => $family->type,
                'birthday' => $family->birthday,
                'name' => $family->name,
                'resume_id' => $resume

            ];

        }

        Family::insert($familiesToInsert);

        $languagesToInsert = [];

        foreach($languages as $language){

            $languagesToInsert[] = [

                'name' => $language->name,
                'level' => $language->level,
                'resume_id' => $resume

            ];
        }

        Language::insert($languagesToInsert);

        $achievmentsToInsert = [];

        foreach($achievments as $achievment){

            $achievmentsToInsert[] = [

                'type' => $achievment->type,
                'description' => $achievment->name,
                'resume_id' => $resume

            ];
        }

        Achievment::insert($achievmentsToInsert);

        return redirect()->back();
    }

    // public function showEdit()
    // {

    // }
}
