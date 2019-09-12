<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $task = Task::with('comments.user')->find(40);
        $users = User::all();
        return view( 'messages.index', compact('users','task') );
    }
}
