<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class CreateProduct extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($from, $product, $project)
    {
        $this->from = $from;
        $this->product = $product;
        $this->project = $project;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $country = $this->project->country->name;
        $pc = $this->project->pc->name;

        return [
            'avatar' => $this->from->img,
            'title' =>  '<a href="' . route("users.show", $this->from->id) . '">' . $this->from->name . ' ' . $this->from->surname . '</a>' .
                ' добавил(a) <a href="' . route("products.show", $this->product->id) . '">' . 'новый продукт' .
            '</a>' . ' в проекте <a href="' . route('products.index',['project_id'=>$this->project->id]) . '">' . $pc . ' · ' . $country . '</a>',
        ];
    }

    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'avatar' => $this->from->img,
            'title' =>  '<a href="' . route("users.show", $this->from->id) . '">' . $this->from->name . ' ' . $this->from->surname . '</a>' .
               ' добавил(a) <a href="' . route("products.show", $this->product->id) . '">' . 'добавил(a) новый продукт' .
                '</a>',
            'notification' => $notifiable->notifications()->latest()->first()
        ]);
        // if procces
    }
}
