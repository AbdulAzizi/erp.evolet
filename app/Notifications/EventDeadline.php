<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class EventDeadline extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task, $time)
    {
        $this->task = $task;
        $this->time = $time;
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
            'avatar' => $this->task->responsible->img,
            'title' => 'Осталось '. $this->time .' до начала выполнения задачи <a href="' . route("tasks.show", $this->task->id) . '">' . mb_strimwidth($this->task->description, 0, 40, "...") . '</a>'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'avatar' => $this->task->responsible->img,
            'title' => 'Осталось '. $this->time .' до начала выполнения задачи <a href="' . route("tasks.show", $this->task->id) . '">' . mb_strimwidth($this->task->description, 0, 40, "...") . '</a>',
            'notification' => $notifiable->notifications()->find($this->id),
        ]);
    }
}