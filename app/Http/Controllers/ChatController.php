<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Task;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::with('division')->get();
        // get chats where auth user is admin or participant
        $chats = Chat::whereHas('participants', function (Builder $query){
                        $query->where('user_id', auth()->user()->id);
                    })
                    ->orWhere('admin_id', auth()->user()->id )
                    ->get();
        
        return view( 'chats.index', compact('users','chats'));
    }

    public function getDetails($id)
    {
        return Chat::with('admin','participants.division', 'comments.user')->find($id);
    }

    public function store(Request $request)
    {
        $chat = Chat::create([
            'title' => $request->title,
            'admin_id' => auth()->user()->id
        ]);
        
        $participants = json_decode($request->participants);
        $chat->participants()->attach($participants);

        return redirect()->route('chats.index');
    }
}
