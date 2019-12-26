<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Product;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MessageCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $product = Product::find($this->message->messageable->id);

        return [
            'avatar' => $this->message->sender->img,
            'title' =>  '<a href="'. route("users.show", $this->message->sender->id) . '">' . $this->message->sender->fullname . '</a>' . 
            ' добавил комментарий ' . '<a href="#">' . $this->message->body . '</a> ' . ' в ' . '<a href="' . route('products.show', $product->id) . '">' . ' продукте ' . '</a>',
        ];
    }

    public function toBroadcast($notifiable)
    {
        $product = Product::find($this->message->messageable->id);
        return new BroadcastMessage([
            'avatar' =>  $this->message->sender->img,
            'title' =>  '<a href="'. route("users.show", $this->message->sender->id) . '">' . $this->message->sender->fullname . '</a>' . 
            'добавил комментарий' . '<a href="#">' . $this->message->body . '</a> ' . ' в ' .  '<a href="' . route('products.show', $product->id) . '">' . ' продукте ' . '</a>',
            'notification' => $notifiable->notifications()->find($this->id)
        ]);
    }
}
