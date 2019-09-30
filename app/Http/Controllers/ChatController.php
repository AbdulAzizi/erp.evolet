<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Comment;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::with('division')->get();
        // get chats where auth user is admin or participant
        $chats = Chat::whereHas('participants', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        })
            ->orWhere('admin_id', auth()->user()->id)
            ->get();

        return view('chats.index', compact('users', 'chats'));
    }

    public function getDetails($id)
    {
        return Chat::with('admin', 'participants.division', 'comments')->find($id);
    }

    public function store(Request $request)
    {
        $chat = Chat::create([
            'title' => $request->title,
            'admin_id' => auth()->user()->id,
        ]);

        $participants = json_decode($request->participants);
        $chat->participants()->attach($participants);

        return redirect()->route('chats.index');
    }

    public function getConversation(Request $request, $userID)
    {
        // return User::whereHas('comments', function (Builder $query){
        //     $query->where('user_id', auth()->user()->id );
        // })->find($userID);

        // return Comment::whereHas('users', function (Builder $query){
        //     $query->where('id', auth()->user()->id );
        // })->get();

        // return User::with('comments')->find($userID);

        $one  = Comment::whereHasMorph(
            'commentable', 
            ['App\User'], 
            function (Builder $query) use ($userID) {
                $query->where('id', $userID);
            }
        )
        ->where('user_id', auth()->user()->id )
        ->get();

        $two  = Comment::whereHasMorph(
            'commentable', 
            ['App\User'], 
            function (Builder $query) use ($userID) {
                $query->where('id', auth()->user()->id);
            }
        )
        ->where('user_id', $userID )
        ->get();

        return $one->merge($two)->sortBy('created_at')->values()->all();

    }
}
