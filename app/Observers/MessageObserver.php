<?php

namespace App\Observers;

use App\Notifications\MessageCreated;
use App\Project;
use App\Message;
use App\Task;
use Illuminate\Support\Facades\Notification;

class MessageObserver
{
    public function created(Message $message)
    {
        if ($message->messageable_type == 'App\Product') {

            $participants = Project::find($message->messageable->project_id)->participants;

            Notification::send($participants, new MessageCreated($message));
        }

        if ($message->messageable_type == 'App\Task') {

            $participants = [];

            $task = Task::find($message->messageable_id);


            foreach ($task->watchers as $watcher) {

                if ($message->sender->id !== $watcher->id) {
                    $participants[] = $watcher;
                }
            }

            if ($message->sender->id !== $task->responsible_id) {
                $participants[] = $task->responsible;
            }

            if ($message->sender->id !== $task->from_id) {
                $participants[] = $task->from;
            }
            
            Notification::send($participants, new MessageCreated($message));
        }
    }
}
