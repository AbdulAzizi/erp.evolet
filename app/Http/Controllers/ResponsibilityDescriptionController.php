<?php

namespace App\Http\Controllers;

use App\Responsibility;
use App\ResponsibilityDescription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ResponsibilityDescriptionController extends Controller
{
    public function create(Request $request)
    {
        $description = ResponsibilityDescription::create([
            'title' => $request->title,
            'description' => $request->description,
            'responsibility_id' => $request->responsibility_id,
            'order' => Responsibility::find( $request->responsibility_id)->descriptions()->count() + 1,
        ]);

        return $description;
    }

    public function edit(Request $request)
    {
        $description = ResponsibilityDescription::find($request->id);

        $description->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $description->save();

        return $description;
    }

    public function delete(Request $request)
    {
        $description = ResponsibilityDescription::find($request->id);

        $description->delete();
    }

    public function moveup(Request $request)
    {
        $description = ResponsibilityDescription::find($request->description_id);

        $position_id = $request->position_id;
        $responsibility_id = $description->responsibility_id;

        $descriptionAbove = ResponsibilityDescription::where('order', $description->order - 1)
                                                     ->whereHas('responsibility', function (Builder $query) use ($position_id, $responsibility_id) {
                                                            $query->where('id', $responsibility_id)
                                                                  ->where('position_id', $position_id);
                                                     })->first();

        $description->order = $description->order - 1;
        $description->save();

        $descriptionAbove->order = $descriptionAbove->order + 1;
        $descriptionAbove->save();

        return 'success';
    }

    public function movedown(Request $request)
    {
        $description = ResponsibilityDescription::find($request->description_id);

        $position_id = $request->position_id;
        $responsibility_id = $description->responsibility_id;

        $descriptionAbove = ResponsibilityDescription::where('order', $description->order + 1)
                                                     ->whereHas('responsibility', function (Builder $query) use ($position_id, $responsibility_id) {
                                                            $query->where('id', $responsibility_id)
                                                                  ->where('position_id', $position_id);
                                                     })->first();

        $description->order = $description->order + 1;
        $description->save();

        $descriptionAbove->order = $descriptionAbove->order - 1;
        $descriptionAbove->save();

        return 'success';
    }
}
