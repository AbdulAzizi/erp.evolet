<?php

namespace App\Events;

use App\History;
use App\Task;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TaskForwardedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($oldResponsible, $task, $reason)
    {
        History::create([
            'user_id' => $oldResponsible->id,
            'description' => 
                    '<a href="' . route("users.dashboard", $oldResponsible->id) . '">' . $oldResponsible->full_name . '</a> 
                    делегировал(a) задачу <a href="' . route("users.dashboard", $task->responsible->id) . '">' . $task->responsible->full_name . '</a>.
                    Причина: "<span class="primary--text">' . $reason . '</span>"',
            'link' => "<a href=" . route("tasks.show", $task->id) . "> $task->description </a>",
            'historyable_id' => $task->id,
            'historyable_type' => 'App\Task',
            'created_at' => date(now())
        ]);
    }

    
}
