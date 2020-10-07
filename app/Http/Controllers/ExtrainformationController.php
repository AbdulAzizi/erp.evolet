<?php

namespace App\Http\Controllers;

use App\Extrainformation;
use Illuminate\Http\Request;

class ExtrainformationController extends Controller
{
    public function create(Request $request)
    {
        return Extrainformation::create([
            'type' => $request->type,
            'description' => $request->description,
            'resume_id' => $request->resume_id
        ]);
    }
    public function delete(Request $request)
    {
        Extrainformation::find($request->id)->delete();

        return 'success';
    }
}
