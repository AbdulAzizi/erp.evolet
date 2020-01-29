<?php

namespace App\Http\Controllers;

use App\Responsibility;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{
    public function create(Request $request)
    {
        $responsibility = Responsibility::create([
            'position_id' => $request->positionId,
            'text' => $request->text
        ]);

        return $responsibility;
    }

    public function edit(Request $request)
    {
        $responsibility = Responsibility::find($request->id);

        $responsibility->text = $request->text;

        $responsibility->save();

        return $responsibility;
    }

    public function delete(Request $request)
    {
        $responsibility = Responsibility::find($request->id);

        $responsibility->descriptions()->delete();
    }
}
