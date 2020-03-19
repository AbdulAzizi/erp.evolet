<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AssignedToTask extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($from, $task)
    {
        $this->from = $from;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // if user
        if (isset($this->from->email))
            return [
                'avatar' => $this->from->img,
                'title' =>  '<a href="' . route("users.dashboard", $this->from->id) . '">' . $this->from->name . ' ' . $this->from->surname . '</a>' .
                    ' поставил(a) вам новую задачу',
                'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' .
                            $this->task->responsibilityDescription->title . '</a>',
                'task' => $this->task->id
            ];
        // if procces
        else
            return [
                'avatar' => null,
                'title' =>  'Процесс <a href="#">' . $this->from->name . '</a>' .
                    ' поставил вам новую задачу',
                'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' .
                            $this->task->responsibilityDescription->title . '</a>',
                'task' => $this->task->id
            ];
    }

    public function toBroadcast($notifiable)
    {
        if (isset($this->from->email))
            return new BroadcastMessage([
                'avatar' => $this->from->img,
                'title' =>  '<a href="' . route("users.dashboard", $this->from->id) . '">' . $this->from->name . ' ' . $this->from->surname . '</a>' .
                            ' поставил(a) вам новую задачу',
                'notification' => $notifiable->notifications()->find($this->id),
                'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' .
                            $this->task->responsibilityDescription->title . '</a>',
                'task' => $this->task->id
            ]);
        // if procces
        else
            return new BroadcastMessage([
                'avatar' => null,
                'title' =>  'Процесс <a href="#">' . $this->from->name . '</a>' .
                    ' поставил вам новую задачу',
                'notification' => $notifiable->notifications()->find($this->id),
                'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' .
                            $this->task->responsibilityDescription->title . '</a>',
                'task' => $this->task->id
            ]);
    }
}
