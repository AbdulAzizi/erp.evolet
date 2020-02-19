<?php

namespace App\Http\Controllers;

use App\Position;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function positions(Request $request, $id)
    {
        $user = User::with([
            'division.positions.responsibilities',
        ])->with(['positions' => function ($positionQuery) use ($id) {
            $positionQuery->with(['responsibilities' => function ($responsibilityQuery) use ($id) {
                $responsibilityQuery->with('descriptions')->whereHas('users', function (Builder $userQuery) use ($id) {
                    $userQuery->where('users.id', $id);
                });
            }]);
        }])->find($id);
        
        $editable = false;
        
        if (\Auth::user()->positionLevel->name == "Руководитель" && \Auth::user()->division->id == $user->division->id) {
            $editable = true;
        }

        return view('profile.positions', compact('user','editable'));
    }
}
