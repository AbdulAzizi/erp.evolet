<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class HistoryCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($history)
    {
        $this->history = $history;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    }
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
        return [
            'avatar' => $this->history->user->img,
            'title' =>  $this->history->description,
            'link' => $this->history->link,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'avatar' => $this->history->user->img,
            'title' => $this->history->description,
            'link' => $this->history->link,
            'notification' => $notifiable->notifications()->find($this->id)
        ]);
    }
}