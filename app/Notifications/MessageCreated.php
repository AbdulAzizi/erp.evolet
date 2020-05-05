<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Product;
use App\Task;
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
        if ($this->message->messageable_type == 'App\Product') {

            $product = Product::find($this->message->messageable->id);

            return [
                'avatar' => $this->message->sender->img,
                'title' =>  '<a href="' . route("profile.positions", $this->message->sender->id) . '">' . $this->message->sender->fullname . '</a>' .
                    ' добавил комментарий ' . '<a href="#">' . mb_strimwidth($this->message->body, 0, 40, '...') . '</a> ' . ' в ' . '<a href="' . route('products.show', $product->id) . '">' . ' продукте ' . '</a>',
            ];
        }
        if ($this->message->messageable_type == 'App\Task') {
            $task = Task::find($this->message->messageable->id);

            return [
                'avatar' => $this->message->sender->img,
                'title' =>  '<a href="' . route("profile.positions", $this->message->sender->id) . '">' . $this->message->sender->fullname . '</a>' .
                    ' добавил комментарий: ' . mb_strimwidth($this->message->body, 0, 40, '...') . ' в ' . '<a href="' . route('tasks.show', $task->id) . '">' . ' ' . mb_strimwidth($task->responsibilityDescription->text, 0, 40, '...') . '</a>',
            ];
        }
    }

    public function toBroadcast($notifiable)
    {
        if ($this->message->messageable_type == 'App\Product') {
            $product = Product::find($this->message->messageable->id);
            return new BroadcastMessage([
                'avatar' =>  $this->message->sender->img,
                'title' =>  '<a href="' . route("profile.positions", $this->message->sender->id) . '">' . $this->message->sender->fullname . '</a>' .
                    'добавил комментарий' . '<a href="#">' . mb_strimwidth($this->message->body, 0, 40, '...') . '</a> ' . ' в ' .  '<a href="' . route('products.show', $product->id) . '">' . ' продукте ' . '</a>',
                'notification' => $notifiable->notifications()->find($this->id)
            ]);
        }
        if ($this->message->messageable_type == 'App\Task') {
            $task = Task::find($this->message->messageable->id);
            return new BroadcastMessage([
                'avatar' =>  $this->message->sender->img,
                'title' =>  '<a href="' . route("profile.positions", $this->message->sender->id) . '">' . $this->message->sender->fullname . '</a>' .
                    'добавил комментарий: ' . mb_strimwidth($this->message->body, 0, 40, '...') . ' в ' .  '<a href="' . route('tasks.show', $task->id) . '">' . ' ' . mb_strimwidth($task->responsibilityDescription->text, 0, 40, '...') . '</a>',
                'notification' => $notifiable->notifications()->find($this->id)
            ]);
        }
    }
}
