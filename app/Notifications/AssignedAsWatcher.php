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
                'title' =>  '<a href="' . route("users.dashboard", $this->from->id) . '">' . $this->from->fullname . '</a>' .
                    ' назначил(а) вас наблюдателем в задаче' ,
                'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' .
                mb_strimwidth( $this->task->description, 0, 40, "...") . '</a>',
                'task' => $this->task->id
            ];
        // if procces
        else
            return [
                'avatar' => null,
                'title' =>  'Процесс <a href="' . route("processes.show", $this->from->id) . '">' . $this->from->name . '</a>' .
                    ' назначил вас наблюдателем в задаче ' . '<a href="' . route("tasks.show", $this->task->id) . '">' .
                    mb_strimwidth( $this->task->description, 0, 40, "...") . '</a>' . 
                    ' Исполнитель: <a href="' . route("users.dashboard", $this->responsible->id) . '">' . $this->responsible->fullname . '</a>',
                'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' .
                    mb_strimwidth( $this->task->description, 0, 40, "...") . '</a>',
                'task' => $this->task->id
            ];
    }

    public function toBroadcast($notifiable)
    {
        if (isset($this->from->email))
            return new BroadcastMessage([
                'avatar' => $this->from->img,
                'title' =>  '<a href="' . route("users.dashboard", $this->from->id) . '">' . $this->from->fullname . '</a>' .
                    ' назначил(а) вас наблюдателем в задаче',
                'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' .
                        mb_strimwidth( $this->task->description, 0, 40, "...") . '</a>',
                'task' => $this->task->id
            ]);
        // if procces
        else
            return new BroadcastMessage([
                'avatar' => null,
                'title' =>  'Процесс <a href="' . route("processes.show", $this->from->id) . '">' . $this->from->name . '</a>' .
                    ' назначил вас наблюдателем в задаче ' . '<a href="' . route("tasks.show", $this->task->id) . '">' .
                    mb_strimwidth( $this->task->description, 0, 40, "...") . '</a>' . 
                    ' Исполнитель: <a href="' . route("users.dashboard", $this->responsible->id) . '">' . $this->responsible->fullname . '</a>',
                'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' .
                    mb_strimwidth( $this->task->description, 0, 40, "...") . '</a>',
                'task' => $this->task->id
            ]);
    }
}
