<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ForwardedTask extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($oldResponsible, $task)
    {
        $this->oldResponsible = $oldResponsible;
        $this->task = $task;
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
        return [
            'avatar' => $this->oldResponsible->img,
            'title' => '<a href="' . route("users.dashboard", $this->oldResponsible->id) . '">' . $this->oldResponsible->full_name . '</a>
                        делегировал(a) задачу <a href="' . route("tasks.show", $this->task->id) . '">' . mb_strimwidth($this->task->description, 0, 40, "...") . '</a>',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'avatar' => $this->oldResponsible->img,
            'title' => '<a href="' . route("users.dashboard", $this->oldResponsible->id) . '">' . $this->oldResponsible->full_name . '</a>
                        делегировал(a) задачу <a href="' . route("tasks.show", $this->task->id) . '">' . mb_strimwidth($this->task->description, 0, 40, "...") . '</a>',
        ]);
    }
}
