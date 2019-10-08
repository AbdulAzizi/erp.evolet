<?php

namespace App\Http\Controllers;

use App\Message;
use App\Events\NewMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function storeApi(Request $request)
    {
        // return $request;
        $message = Message::create([
            'user_id' => auth()->user()->id,
            'body' => $request->body,
            'messageable_id' => $request->messageable_id,
            'messageable_type' => $request->messageable_type
        ]);
        
        $message->load('sender');

        event(new NewMessage($message->messageable_id, $message->messageable_type, $message));
        
        return $message;
    }
}
