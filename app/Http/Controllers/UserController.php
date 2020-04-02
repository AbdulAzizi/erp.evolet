<?php

namespace App\Http\Controllers;

use App\Division;
use App\File;
use App\Position;
use App\PositionLevel;
use App\ResponsibilityDescription;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Image;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $users = User::with('division')->get();

        return view('users.index', compact('users'));
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->back();
    }

    public function usersForHr()
    {
        $division = Division::with('users', 'head')->withDepth()->descendantsAndSelf(1)->toTree()->first();
        // $users = User::with('division')->get();

        return view('hr.users', compact('division'));
    }

    public function edit(Request $request)
    {
        $user = User::find($request->id);

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

    public function mark(Request $request)
    {
        $task = Task::find($request->task_id);

        $task->readed = 1;
        $task->save();

        return 'success';
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        $user = User::find($request->id);

        $notifications = $user->notifications->markAsRead();

        return $notifications;
    }

    public function getUsers()
    {
        return User::all();
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

    public function responsibilityDescription(Request $request)
    {
        $userIDs = $request->userIDs;
        $users = User::with(['responsibilities.descriptions'])->find($userIDs);
        $descriptionGroups = [];

        foreach ($users as $key => $user) {
            foreach ($user->responsibilities as $responsibility) {
                foreach ($responsibility->descriptions as $description) {
                    $descriptionGroups[$key][] = $description;
                }
            }
        }

        $intersactions = collect([]);

        if (count($descriptionGroups)) {
            $intersactions = collect($descriptionGroups[0]);
            foreach ($descriptionGroups as $descriptionGroup) {
                $intersactions = $intersactions->intersect($descriptionGroup);
            }
        }

        $others = ResponsibilityDescription::where('title', 'Прочее')->first();
        $intersactions->push($others);
        
        return $intersactions->all();
    }

    public function detachPosition($userID, $positionID)
    {
        $user = User::find($userID);
        $user->positions()->detach($positionID);

        $respsToDetach = Position::find($positionID)->responsibilities->pluck('id');
        $user->responsibilities()->detach($respsToDetach);

        return 'success';
    }
}
