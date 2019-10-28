<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $type;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $id , $type, $message)
    {
        $this->id = $id;
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $authID = auth()->user()->id;
        $result = explode('\\', $this->type);
        $model = $result[1];

        if($this->type == 'App\User')
            //     Model.messageableID.authID.messages
            // Ex: User.2.4.messages
            return new Channel("$model.$this->id.$authID.messages");
        else
            // Ex: Task.4.messages
            return new Channel("$model.$this->id.messages");
    }
}
