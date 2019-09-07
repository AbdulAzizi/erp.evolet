<?php

namespace App\Events;

use App\Product;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ProductCreatedEvent extends HistoryEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $author = auth()->user();

        $this->historyDescription = "Пользователь <a href='/users/$author->id'>$author->full_name</a> добавил продукт.";

        $this->historyItem = $product->id;
        $this->historyType = Product::class;
    }

}
