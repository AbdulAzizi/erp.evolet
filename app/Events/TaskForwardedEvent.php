<?php

namespace App\Events;

use App\Task;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TaskForwardedEvent extends HistoryEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $oldTask, Task $newTask)
    {
        $oldResponsible = $oldTask->load('responsible')->responsible;
        $newResponsible = $newTask->load('responsible')->responsible;

        $this->historyDescription = "Пользователь <a href='/users/$oldResponsible->id'>$oldResponsible->full_name</a> делегировал задачу пользователю <a href='/users/$newResponsible->id'>$newResponsible->full_name</a>";
        $this->historyItem = $newTask->id;
        $this->historyType = Task::class;
    }

    
}
