<?php

namespace App\Observers;

use App\History;
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
        // Dont notify when author and assignee are the same person
        if( $task->responsible->id !=  $task->from->id && $task->from_type == "App\User"){
            // Notify Assignees
            $task->responsible->notify(new AssignedToTask($task->from, $task));
            // Notify Author
            // $task->from->notify(new AssignedAsAuthor($task));
        }

        if ($task->from_type == "App\User") {

            // Make flash notification
            $alerts = session()->get('alerts');
            if ($alerts) {
                $alerts[] = "Успешно добавили задачу!";
            } else {
                $alerts = ["Успешно добавили задачу!"];
            }

            session()->flash('alerts', $alerts);
            
            History::create([
                'user_id' => $task->from->id,
                'description' => 
                        '<a href="' . route("users.dashboard", $task->from->id) . '">' . $task->from->fullname . '</a> поставил(a) задачу
                         <a href="' . route("users.dashboard", $task->responsible->id) . '">' . $task->responsible->fullname . '</a>',
                'link' => "<a href=" . route("tasks.show", $task->id) . "> $task->description </a>",
                'historyable_id' => $task->id,
                'historyable_type' => 'App\Task',
                'created_at' => date(now())
            ]);
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
