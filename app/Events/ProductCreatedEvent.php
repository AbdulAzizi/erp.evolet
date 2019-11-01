<?php

namespace App\Events;

use App\History;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ProductCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct($product)
    {
        $user = auth()->user();
        $project = $product->project->load('country', 'pc');

        History::create([
            'user_id' => $user->id,
            'description' => 
                'Пользователь <a href="' . route('users.show', $user->id) . '">' . $user->fullname . '</a> добавил новый 
                <a href="' . route("products.show", $product->id) . '">продукт</a> в проекте 
                <a href="' . route('products.index',['project_id'=>$project->id]) . '">' . $project->country->name . ' · ' . $project->pc->name . '</a>',
            'historyable_id' => $product->id,
            'historyable_type' => 'App\Product',
            'created_at' => date(now())
        ]);
    }

}
