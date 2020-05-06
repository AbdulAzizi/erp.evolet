<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class TaskIsStarted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task)
    {
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
            'avatar' => $this->task->responsible->img,
            'title' => '<a href="' . route("users.dashboard", $this->task->responsible->id) . '">' . $this->task->responsible->full_name . '</a>
                        начал(а) выполнять вашу задачу <a href="' . route("tasks.show", $this->task->id) . '">' . mb_strimwidth($this->task->description, 0, 40, "...") . '</a>',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'avatar' => $this->task->responsible->img,
            'title' => '<a href="' . route("users.dashboard", $this->task->responsible->id) . '">' . $this->task->responsible->full_name . '</a>
                        начал(а) выполнять вашу задачу <a href="' . route("tasks.show", $this->task->id) . '">' . mb_strimwidth($this->task->description, 0, 40, "...") . '</a>',
            'notification' => $notifiable->notifications()->find($this->id),
        ]);
    }
}
