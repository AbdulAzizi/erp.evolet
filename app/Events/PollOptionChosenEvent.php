<?php

namespace App\Events;

use App\History;
use App\Option;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PollOptionChosenEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $questionTask;
    
    public function __construct($questionTask, $user, $selectedOptionID)
    {
        $this->questionTask = $questionTask;
        $option = Option::find($selectedOptionID);
        $product = $questionTask->task->products->first();

        History::create([
            'user_id' => $user->id,
            'description' => 
                'Пользователь <a href="' . route('users.dashboard',$user->id) . '">' . $user->fullname . '</a> 
                выбрал опцию <span class="primary--text">'.$option->body.'</span> в опросе <span class="primary--text">'.$questionTask->question->body.'</span>',
            'historyable_id' => $product->id,
            'historyable_type' => 'App\Product',
            'created_at' => date(now())
        ]);
    }
    
    public function broadcastOn()
    {
        return new Channel('questionTasks.' . $this->questionTask->id);
    }
}
