<?php

namespace App\Http\Controllers;

use App\Division;
use App\File;
use App\Position;
use App\PositionLevel;
use App\Responsibility;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Image;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $division = Division::with('users', 'head')->withDepth()->descendantsAndSelf(1)->toTree()->first();
        
        return view('users.index', compact('division'));
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->forceDelete();

        return redirect()->back();
    }

    public function edit(Request $request)
    {
        $user = User::find(auth()->user()->id);
        
        if ($request->email) {

            $user->email = $request->email;

        } else {

            $user->name = $request->name;

            $user->surname = $request->surname;
        }

        $user->save();

        return $user;
    }

    public function store(Request $request)
    {

        $messages = [
            'email.unique' => 'Пользователь с таким адресом существует',
            'email.email' => 'Введен неправильный адрес',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ], $messages);

        $randomPassword = Str::random(10);

        $positionLevel = $request->headEmployee ? PositionLevel::where('name', 'Руководитель')->first() : PositionLevel::find($request->positionId);

        if ($validator->fails()) {
            return ['emailError' => $validator->errors()->first()];
        } else {
            $newUser = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => $randomPassword,
                'position_level_id' => $request->headEmployee ? $positionLevel->id : $request->positionId,
                'division_id' => $request->divisionId,
            ]);
        }

        if ($request->positions) {
            $newUser->positions()->attach($request->positions);
        }

        if ($positionLevel->name == 'Руководитель') {

            $secondaryPositionId = PositionLevel::where('name', 'Главный специалист')->first()->id;

            $division = Division::find($request->divisionId);

            $headUser = User::find($division->head_id);

            if ($headUser) {

                $headUser->position_level_id = $secondaryPositionId;

                $headUser->save();
            } else {

                $division->head_id = $newUser->id;
            }

            $division->save();
        }
        // SendsPasswordResetEmails::sendResetLinkEmail(new Request (['email' => $newUser->email]) );
        Password::broker()->sendResetLink(['email' => $newUser->email]);

        $user = User::with('positionLevel', 'positions')->find($newUser->id);
        return $user;
    }

    public function markAsRead(Request $request)
    {
        $user = User::find($request->user_id);

        $notification = $user->notifications->find($request->notification['id']);

        if ($request->toggle == true) {

            $notification->read_at !== null ? $notification->markAsUnread() : $notification->markAsRead();

        } else if ($request->toggle == false) {

            $notification->markAsRead();
        }

        return $notification;
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        $user = User::find($request->id);

        $notifications = $user->notifications->markAsRead();

        return $notifications;
    }

    public function loadNotifications(Request $request) 
    {

        $user = User::find(auth()->user()->id);
        
        $notifications = $user->notifications->forPage($request->page, 10)->values();

        return $notifications;
    }

    public function getUsers(Request $request)
    {
        $query = User::query();

        if($request->division){
            $query->with('division');
        }

        return $query->get();
    }

    public function changeAvatar(Request $request)
    {
        $user = User::find($request->id);

        if ($request->hasFile('avatar')) {

            $avatar = $request->file('avatar');
            $filename = $user->name . $user->surname . '.' . $avatar->getClientOriginalExtension();

            if (Storage::exists($filename)) {
                Storage::delete($filename);
            }

            $orientateAvatar = Image::make($avatar)->fit(250)->orientate()->save(public_path("/img/" . $filename));

            $orientateThumb = Image::make($avatar)->fit(80)->orientate()->save(public_path("/img/thumbs/" . $filename))->orientate();

            $user->img = $filename;
            $user->save();
        }

        return redirect()->back();
    }

    public function responsibilities(Request $request)
    {
        // $userIDs = $request->userIDs;
        // $users = User::with(['responsibilities.descriptions'])->find($userIDs);
        // $descriptionGroups = [];

        // foreach ($users as $key => $user) {
        //     foreach ($user->responsibilities as $responsibility) {
        //         foreach ($responsibility->descriptions as $description) {
        //             $descriptionGroups[$key][] = $description;
        //         }
        //     }
        // }

        // $intersactions = collect([]);

        // if (count($descriptionGroups)) {
        //     $intersactions = collect($descriptionGroups[0]);
        //     foreach ($descriptionGroups as $descriptionGroup) {
        //         $intersactions = $intersactions->intersect($descriptionGroup);
        //     }
        // }

        // $others = ResponsibilityDescription::where('title', 'Прочее')->first();
        // $intersactions->push($others);

        // return $intersactions->all();
        $userIDs = $request->userIDs;
        $responsibilitiesToReturn = collect([]);
        if (count($userIDs) == 1) {
            $responsibilitiesToReturn = User::with(['responsibilities' => function ($query) {
                $query->has('descriptions')->with('descriptions');
            }])->find($userIDs)->first()->responsibilities;
        }
        $others = Responsibility::with('descriptions')->where('text', 'Прочее')->first();
        $responsibilitiesToReturn->push($others);
        return $responsibilitiesToReturn;
    }

    public function detachPosition($userID, $positionID)
    {
        $user = User::find($userID);
        $user->positions()->detach($positionID);

        $respsToDetach = Position::find($positionID)->responsibilities->pluck('id');
        $user->responsibilities()->detach($respsToDetach);

        return 'success';
    }

    public function responsibilitydescriptions($id)
    {
        $user = User::with('responsibilities.descriptions')->find($id);
        $descriptions = [];

        $others = Responsibility::with('descriptions')->where('text', 'Прочее')->first();
        $descriptions[] = $others->descriptions->first();

        foreach ($user->responsibilities as $responsibility) {
            foreach ($responsibility->descriptions as $description) {
                $descriptions[] = $description;
            }
        }
        return $descriptions;
    }

    public function tasks()
    {
        return view('users.tasks');
    }

    public function fire($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->back();
    }

    public function fired()
    {
        $users = User::onlyTrashed()->get();
        return view('users.fired',compact('users'));
    }
}
