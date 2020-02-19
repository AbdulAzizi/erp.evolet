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
        ]);

        $responsibilityWithDescriptions = Responsibility::with('descriptions')->find($responsibility->id);

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
}
