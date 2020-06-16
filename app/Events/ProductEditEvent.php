<?php

namespace App\Events;

use App\History;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductEditEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($product, $val)
    {
        $user = auth()->user();
        $project = $product->project->load('country', 'pc');

        History::create([
            'user_id' => $user->id,
            'description' => 
                'Пользователь <a href="' . route('users.dashboard', $user->id) . '">' . $user->fullname . '</a> изменил поле ' . " \"$val\" " .
                "</a> проекта " . 
                ' <a href="' . route('products.index',['project_id' => $project->id]) . '">' . $project->country->name . ' · ' . $project->pc->name . '</a>',
            'historyable_id' => $product->id,
            'link' => ' <a href="' . route("products.show", $product->id) . '">' . " в продукте "  . " $product->name " .  "</a>",
            'historyable_type' => 'App\Product',
            'created_at' => date(now())
        ]);
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
