<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignedAsWatcher extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($from, $responsible, $task)
    {
        $this->from = $from;
        $this->task = $task;
        $this->responsible = $responsible;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                'title' =>  '<a href="' . route("users.show", $this->from->id) . '">' . $this->from->name . ' ' . $this->from->surname . '</a>' .
                    ' Назначил(а) вас наблюдателем в задаче <a href="' . route("tasks.show", $this->task->id) . '">' .
                    $this->task->title . '</a>' . '<a href="' . route("users.show", $this->responsible->id) . '">' .  ' исполнитель: ' . $this->responsible->name . '</a>',
            ];
        // if procces
        else
            return [
                'avatar' => null,
                'title' =>  'Процесс <a href="' . route("processes.show", $this->from->id) . '">' . $this->from->name . '</a>' .
                    ' назначил(а) вас наблюдателем в задаче <a href="' . route("tasks.show", $this->task->id) . '">' .
                    $this->task->title . '</a>' . '<a href="' . route("users.show", $this->responsible->id) . '">' .  ' исполнитель: ' . $this->responsible->name . '</a>',
            ];
    }

    public function toBroadcast($notifiable)
    {
        if (isset($this->from->email))
            return new BroadcastMessage([
                'avatar' => $this->from->img,
                'title' =>  '<a href="' . route("users.show", $this->from->id) . '">' . $this->from->name . ' ' . $this->from->surname . '</a>' .
                    ' Назначил(а) вас наблюдателем в задаче <a href="' . route("tasks.show", $this->task->id) . '">' .
                    $this->task->title . '</a>' . '<a href="' . route("users.show", $this->responsible->id) . '">' .  ' исполнитель: ' . $this->responsible->name . '</a>',
            ]);
        // if procces
        else
            return new BroadcastMessage([
                'avatar' => null,
                'title' =>  'Процесс <a href="' . route("processes.show", $this->from->id) . '">' . $this->from->name . '</a>' .
                    ' назначил(а) вас наблюдателем в задаче <a href="' . route("tasks.show", $this->task->id) . '">' .
                    $this->task->title . '</a>' . '<a href="' . route("users.show", $this->responsible->id) . '">' . ' исполнитель: ' . $this->responsible->name . '</a>',
            ]);
    }
}
