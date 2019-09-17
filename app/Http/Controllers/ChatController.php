<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Task;
use App\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::all();
        $chats = Chat::with('admin','participants', 'comments.user')->get();
        return view( 'chats.index', compact('users','chats'));
    }
}
