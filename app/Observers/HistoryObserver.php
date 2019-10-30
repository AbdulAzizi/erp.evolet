<?php

namespace App\Observers;

use App\Product;
use App\Project;
use App\History;
use App\Notifications\HistoryCreated;
use Illuminate\Support\Facades\Notification;

class HistoryObserver
{
    public function created(History $history)
    {
        if ($history->happened_with_type == 'App\Product') {
    
            $product = Product::find($history->happened_with_id);
    
            $project = Project::find($product->project_id);
    
            Notification::send($project->participants, new HistoryCreated($history, $product, $project));
        }
}
}
