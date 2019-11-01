<?php

namespace App\Observers;

use App\History;
use App\Notifications\HistoryCreated;
use Illuminate\Support\Facades\Notification;

class HistoryObserver
{
    public function created(History $history)
    {
        if ($history->historyable_type == 'App\Product') {
    
            Notification::send($history->historyable->project->participants, new HistoryCreated($history));
        }
    }
}
