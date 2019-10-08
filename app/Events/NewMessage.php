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
        switch ($this->type) {
            case 'App\User':
                return new Channel("newMessage.Users.$this->id.$authID");
                break;
            
            default:
                return new Channel("newMessage.Chats.$this->id");
                break;
        }
    }
}
