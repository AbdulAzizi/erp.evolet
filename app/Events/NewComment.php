<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $type;
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $id , $type, $comment)
    {
        $this->id = $id;
        $this->type = $type;
        $this->comment = $comment;
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
                return new Channel("newComment.Users.$this->id.$authID");
                break;
            
            default:
                return new Channel("newComment.Chats.$this->id");
                break;
        }
    }
}
