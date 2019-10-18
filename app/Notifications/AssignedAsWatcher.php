<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
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
    public function __construct( $from, $responsible, $task )
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
        // $from = $this->from->name;
        // $title = $this->task->title;
        // $responsible = $this->responsible->name;

        return [
            'avatar' => $this->from->img,
            'title' =>  'Процесс <a href="'.route("processes.show", $this->from->id).'">'.$this->from->name.'</a>'.
                        ' назначил(а) вас наблюдателем в задаче:
                        <a href="' . route("tasks.show", $this->task->id) . '">' . $this->task->title . '</a> 
                        Исполнитель:<a href="' . route("users.show", $this->responsible->id) . '">' . $this->responsible->name . '</a>'
        ];
    }
}
