<?php

namespace App\Observers;

use App\Notifications\MessageCreated;
use App\Project;
use App\Message;
use Illuminate\Support\Facades\Notification;

class MessageObserver
{
    public function created(Message $message)
    {
        if($message->messageable_type == 'App\Product'){

            $participants = Project::find($message->messageable->project_id)->participants;

            Notification::send($participants, new MessageCreated($message));        
        }
    }
}
