<?php

namespace App\Http\Controllers;

use App\ResponsibilityDescription;
use Illuminate\Http\Request;

class ResponsibilityDescriptionController extends Controller
{
    public function create(Request $request)
    {
        $description = ResponsibilityDescription::create([
            'title' => $request->title,
            'description' => $request->description,
            'responsibility_id' => $request->responsibility_id
        ]);

        return $description;
    }

    public function edit(Request $request)
    {
        $description = ResponsibilityDescription::find($request->id);

        $description->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        $description->save();

        return $description;
    }

    public function delete(Request $request)
    {
        $description = ResponsibilityDescription::find($request->id);

        $description->delete();
    }
}
