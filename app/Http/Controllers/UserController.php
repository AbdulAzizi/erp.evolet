<?php

namespace App\Http\Controllers;

use App\User;
use App\Resume;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Validator as ValidationValidator;

class UserController extends Controller
{


    public function index(Request $request)
    {

        $users = User::with('division')->get();

        return view('profile.index', compact('users'));
    }

    public function show(Request $request)
    {

        $user = User::find($request->id);

        return view('profile.tasks', compact('user'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users,email',
            'positionId' => 'required',
            'responsibilityId' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $randomPassword = Str::random(10);

        $newUser = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => $randomPassword,
            'position_id' => $request->positionId,
            'division_id' => $request->divisionId,
        ]);

        $responsibilityIds = json_decode($request->responsibilityId);
        $newUser->responsibilities()->attach($responsibilityIds);

        Password::broker()->sendResetLink(['email' => $newUser->email]);

        return redirect()->back();
    }

    public function markAsRead(Request $request)
    {
        $user = User::find($request->user_id);

        $notification = $user->notifications->find($request->notification['id']);

        if($request->toggle == true){
            
            $notification->read_at !== null ? $notification->markAsUnread() : $notification->markAsRead();
        }
        else if($request->toggle == false) {

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
}
