<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Direct;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $directsStartedByMe = Direct::where('from', auth()->user()->id)
            ->with('to.division', 'messages')
            ->has('messages')
            ->get();

        $directsStartedByOthers = Direct::where('to', auth()->user()->id)
            ->with('from.division', 'messages')
            ->has('messages')
            ->get();

        $chats = $directsStartedByMe->merge($directsStartedByOthers);

        $messagedGroups = Chat::whereHas('participants', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        })
            ->orWhere('admin_id', auth()->user()->id)
            ->has('messages')
            ->with('messages')
            ->get();

        $chats = $messagedGroups->toBase()->merge($chats)->sortByDesc('last_message.created_at')->values()->all();

        $users = User::with('division')->get();
        // get chats where auth user is admin or participant
        $groups = Chat::whereHas('participants', function (Builder $query) {
            $query->where('user_id', auth()->user()->id);
        })
            ->orWhere('admin_id', auth()->user()->id)
            ->with('messages')
            ->get();

        return view('chats.index', compact('users', 'chats', 'groups'));
    }

    public function getDetails($id)
    {
        return Chat::with('admin', 'participants.division', 'messages')->find($id);
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

    public function getDirect(Request $request, $userID)
    {
        $direct = Direct::where([
            ['from', auth()->user()->id],
            ['to', $userID],
        ])
            ->orWhere([
                ['from', $userID],
                ['to', auth()->user()->id],
            ])
            ->with('messages')
            ->first();
        if (!$direct) {
            $direct = Direct::create([
                'from' => auth()->user()->id,
                'to' => $userID,
            ]);
        }

        $user = User::find($userID);

        $direct['title'] = "$user->name $user->surname";

        $direct->load('messages');

        return $direct;
    }
}
