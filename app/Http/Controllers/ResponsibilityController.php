<?php

namespace App\Http\Controllers;

use App\Position;
use App\Responsibility;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{
    public function create(Request $request)
    {
        $responsibility = Responsibility::create([
            'position_id' => $request->positionId,
            'text' => $request->text,
            'order' => Position::find($request->positionId)->responsibilities()->count() + 1,
        ]);

        $responsibility->descriptions = [];
        $responsibilityWithDescriptions = $responsibility;

        return $responsibilityWithDescriptions;
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

        // find all description that are below
        $lowerResponsibilities = Responsibility::where('order','>', $responsibility->order)
                                                ->where('position_id', $responsibility->position_id)
                                                ->get();
        // lopp through all of them
        foreach ($lowerResponsibilities as $lowerResponsibility) {
            // decrease order by 1
            $lowerResponsibility->order = $lowerResponsibility->order - 1;
            // save description
            $lowerResponsibility->save();
        }

        $responsibility->descriptions()->delete();

        $responsibility->delete();
    }

    public function attachUser(Request $request, $id)
    {
        $user = User::find($id);
        $user->positions()->syncWithoutDetaching($request->position);

        $respsToDetach = Position::find($request->position)->responsibilities->pluck('id');
        $user->responsibilities()->detach($respsToDetach);

        $user->responsibilities()->syncWithoutDetaching($request->responsibilities);

        $user = User::with([
            'division.positions.responsibilities',
        ])->with(['positions' => function ($positionQuery) use ($id) {
            $positionQuery->with(['responsibilities' => function ($responsibilityQuery) use ($id) {
                $responsibilityQuery->with('descriptions')->whereHas('users', function (Builder $userQuery) use ($id) {
                    $userQuery->where('users.id', $id);
                });
            }]);
        }])->find($id);

        return $user;
    }

    public function moveup($id)
    {
        $responsibility = Responsibility::find($id);
        $responsibilityAbove = Responsibility::where('order', $responsibility->order - 1)
                                             ->where('position_id', $responsibility->position_id)
                                             ->first();

        $responsibility->order = $responsibility->order - 1;
        $responsibility->save();

        $responsibilityAbove->order = $responsibilityAbove->order + 1;
        $responsibilityAbove->save();

        return 'success';
    }

    public function movedown($id)
    {
        $responsibility = Responsibility::find($id);
        $bottomResponsibility = Responsibility::where('order', $responsibility->order + 1)
                                             ->where('position_id', $responsibility->position_id)
                                             ->first();

        $responsibility->order = $responsibility->order + 1;
        $responsibility->save();

        $bottomResponsibility->order = $bottomResponsibility->order - 1;
        $bottomResponsibility->save();

        return 'success';
    }
}
