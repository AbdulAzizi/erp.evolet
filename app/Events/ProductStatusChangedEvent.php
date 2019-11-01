<?php

namespace App\Events;

use App\History;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ProductStatusChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public function __construct($product, $process)
    {
        $user = auth()->user();

        History::create([
            'user_id' => $user->id,
            'description' => 
                $user->fullname . ' закрыл задачу. Статус процесса изменен на <a href="#">' .  $process->name . '</a>',
            'historyable_id' => $product->id,
            'historyable_type' => 'App\Product',
            'created_at' => date(now())
        ]);
    }
}
