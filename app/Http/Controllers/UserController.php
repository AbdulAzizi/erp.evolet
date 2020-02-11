<?php

namespace App\Http\Controllers;

use App\Division;
use App\File;
use App\PositionLevel;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $users = User::with('division')->get();

        return view('users.index', compact('users'));
    }

    public function show(Request $request)
    {

        $user = User::find($request->id);

        return view('users.show', compact('user'));
    }

    public function usersForHr()
    {
        $division = Division::with('users','head')->get()->toTree()->first();
        // $users = User::with('division')->get();

        return view('hr.users', compact('division'));
    }

    public function store(Request $request)
    {
        $randomPassword = Str::random(10);

        $positionLevel = PositionLevel::find($request->positionId);

        $newUser = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => $randomPassword,
            'position_level_id' => $request->positionId,
            'division_id' => $request->divisionId,
        ]);

        $newUser->positions()->attach($request->positions);

        if ($positionLevel->name == 'Руководитель') {

            $secondaryPositionId = PositionLevel::where('name', 'Главный специалист')->first()->id;

            $division = Division::find($request->divisionId);

            $headUser = User::find($division->head_id);

            $headUser->position_level_id = $secondaryPositionId;
            $headUser->save();

            $division->head_id = $newUser->id;
            $division->save();
        }
        // SendsPasswordResetEmails::sendResetLinkEmail(new Request (['email' => $newUser->email]) );
        // Password::broker()->sendResetLink(['email' => $newUser->email]);

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

    public function hrCreateUser(Request $request)
    {
        $user = User::create([
            'name' => $request->user['name'],
            'surname' => $request->user['surname'],
            'email' => $request->user['email'],
            'password' => Str::random(10),
            'position_level_id' => $request->user['positionLevel'],
            'division_id' => $request->user['division'],
        ]);
        // // Generate a new reset password token
        // $token = app('auth.password.broker')->createToken($user);
        // $user->notify(new SetupPassword($token));
        Password::broker()->sendResetLink(['email' => $user->email]);

        $userWithRelations = User::with('division')->find($user->id);

        return $userWithRelations;
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

            Image::make($avatar)->fit(250)->save(public_path("/img/" . $filename));

            Image::make($avatar)->fit(80)->save(public_path("/img/thumbs/" . $filename));

            $user->img = $filename;
            $user->save();
        }

        return redirect()->back();
    }
}
