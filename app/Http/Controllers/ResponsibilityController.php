<?php

namespace App\Http\Controllers;

use App\Division;
use App\JobDescription;
use App\User;
use App\Responsibility;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{
    public function index()
    {
        $responsibilities = Responsibility::with('descriptions')->get();
        return view('responsibilites', compact('responsibilities'));
    }

    public function show(Request $request, $id)
    {
        $user = User::with('responsibilities.descriptions')->find($request->id);
        $division = new Division();

        if (\Auth::user()->position->name = "Руководитель" && \Auth::user()->division->id == $user->division->id)
            $division = Division::with('responsibilities')->find($user->division_id);
        return view('profile.responsibilities', compact('user', 'division'));
    }

    public function store(Request $request)
    {
        $data = [];

        $responsibility = Responsibility::create([
            'name' => $request->name,
            'division_id' => $request->id
        ]);


        foreach ($request->descriptions as $description) {
            if ($description['level'] !== null && $description['text'] !== null) {
                $data[] = [
                    'responsibility_id' => $responsibility->id,
                    'text' => $description['text'],
                    'level' => $description['level'],
                    'planned_time' => $description['days'] * 86400000 + $description['hours'] * 3600000 + $description['minutes'] * 60000
                ];
            }
        }
        $jobDescription = JobDescription::insert($data);

        $responsibilityWithDescriptions = Responsibility::with('descriptions')->find($responsibility->id);

        return $responsibilityWithDescriptions;
    }
    public function createJobDescription()
    {
        JobDescription::create([
            'responsibility_id' => request('responsibility_id'),
            'text' => request('text')
        ]);

        return  redirect()->back();
    }

    public function loadResponsibilities()
    {
        $responsibilites = Responsibility::all();

        return $responsibilites;
    }

    public function loadDivisionResponsibilities(Request $request)
    {
        $divisionResponsibilities = Responsibility::where('division_id', $request->id)->get();

        return $divisionResponsibilities;
    }

    public function addResponsibility(Request $request)
    {
        $data = [];

        foreach ($request->descriptions as $description) {
            if ($description['level'] !== null && $description['text'] !== null) {
            $data[] = [
                'responsibility_id' => $request->id,
                'text' => $description['text'],
                'level' => $description['level'],
                'planned_time' => $description['days'] * 86400000 + $description['hours'] * 3600000 + $description['minutes'] * 60000
            ];
        }
        }

        $jobdescription = JobDescription::insert($data);

        $responsibility = Responsibility::with('descriptions')->find($request->id);

        return $responsibility;
    }
}
