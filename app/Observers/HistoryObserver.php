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
        //
    }
}
