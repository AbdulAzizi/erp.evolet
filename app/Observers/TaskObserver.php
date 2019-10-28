<?php

namespace App\Observers;

use App\Notifications\AssignedAsAuthor;
use App\Notifications\AssignedToTask;
use App\Task;

class TaskObserver
{
    public function retrieved(Task $task)
    {
        //
    }
    public function creating(Task $task)
    {
        //
    }
    public function created(Task $task)
    {
        // Notify Assignees
        $task->responsible->notify(new AssignedToTask($task->from, $task));

        if ($task->from_type == "App\User") {
            // Notify Author
            $task->from->notify(new AssignedAsAuthor($task));

            // Make flash notification
            $alerts = session()->get('alerts');
            if ($alerts) {
                $alerts[] = "Успешно добавили задачу!";
            } else {
                $alerts = ["Успешно добавили задачу!"];
            }

            session()->flash('alerts', $alerts);
        }

    }
    public function updating(Task $task)
    {
        //
    }
    public function updated(Task $task)
    {
        //
    }
    public function saving(Task $task)
    {
        //
    }
    public function saved(Task $task)
    {
        //
    }
    public function deleting(Task $task)
    {
        //
    }
    public function deleted(Task $task)
    {
        //
    }
    public function forceDeleted(Task $task)
    {
        //
    }
    public function restoring(Task $task)
    {
        //
    }
    public function restored(Task $task)
    {
        //
    }
}
