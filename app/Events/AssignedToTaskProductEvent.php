<?php

namespace App\Events;

use App\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AssignedToTaskProductEvent extends HistoryEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($product, $process, $task, $responsible)
    {

        $this->historyDescription = "Процесс <a href='#'> $process->name </a> поставил задачу
                                     <a href=" . route("tasks.show", $task->id) . "> $task->title </a> пользователю
                                     <a href=" . route("users.show", $responsible->id) . "> $responsible->fullname </a>";

        $this->historyItem = $product->id;

        $this->historyType = Product::class;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
