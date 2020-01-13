<?php

namespace App\Events;

use App\History;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AssignedToTaskProductEvent
{
    use Dispatchable, SerializesModels;

    public function __construct($product, $process, $task, $responsible)
    {
        History::create([
            'user_id' => $responsible->id,
            'description' => 
                    "Процесс <a href='#'> $process->name </a> поставил пользователю
                    <a href=" . route("users.show", $responsible->id) . "> $responsible->fullname </a> задачу ",
            'link' => "<a href=" . route("tasks.show", $task->id) . "> $task->title </a>",
            'historyable_id' => $product->id,
            'historyable_type' => 'App\Product',
            'created_at' => date(now())
        ]);
    }
}
