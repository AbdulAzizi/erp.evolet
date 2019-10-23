<?php

namespace App\Observers;

use App\Product;
use App\Project;
use App\History;
use App\Task;
use App\User;
use App\Notifications\AssignedAsWatcher;
use App\Notifications\AssignedToTask;
use App\Notifications\CreateProduct;
use Illuminate\Support\Facades\Notification;

class HistoryObserver
{
    public function created(History $history)
    {

        $user = User::find($history->user_id);

        if ($history->happened_with_type == 'App\Product') {

            $product = Product::find($history->happened_with_id);

            $project = Project::find($product->project_id);

            Notification::send($project->participants, new CreateProduct($user, $product, $project));
        }

        if ($history->happened_with_type == 'App\Task') {

            $task = Task::find($history->happened_with_id);

            $watchers = $task->watchers;

            $responsible = User::find($task->responsible_id);

            if (count($watchers)) {
                Notification::send($watchers, new AssignedAsWatcher($task->from, $responsible, $task));
            }


            Notification::send($responsible, new AssignedToTask($task->from, $task));
        }
    }
}
