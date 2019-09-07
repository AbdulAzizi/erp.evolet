<?php

namespace App\Events;

use App\Task;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TaskCreatedEvent extends HistoryEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $author = auth()->user();

        $this->historyDescription = "Пользователь <a href='/users/$author->id'>$author->full_name</a> добавил задачу.";

        $this->historyItem = $task->id;
        $this->historyType = Task::class;
    }

}
