<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use \Illuminate\Support\Str;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AssignedAsAuthor extends Notification
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
        // 'broadcast'
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
            'title' =>  'Вы автор задачи:',
            'link' => '<a href="' . route("tasks.show", $this->task->id) . '">' . Str::limit($this->task->description, 40, '...') . '</a>',
            'task' => $this->task->id
        ];
    }
    
    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'avatar' => $this->task->responsible->img,
    //         'title' =>  'Вы автор задачи: <a href="' . route("tasks.show", $this->task->id) . '">' . $this->task->responsibilityDescription->title . '</a>'
    //     ]);
    // }
    
}
