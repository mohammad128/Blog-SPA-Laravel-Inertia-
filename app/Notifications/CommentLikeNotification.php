<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentLikeNotification extends Notification
{
    use Queueable;

    private $comment, $type, $active, $modelUrl, $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $comment, $type, $active, $modelUrl)
    {
        $this->user = $user;
        $this->comment = $comment;
        $this->type = $type;
        $this->active = $active;
        $this->modelUrl = $modelUrl;
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
        return [
            'user' => $this->user,
            'comment' => $this->comment,
            'type' => $this->type,
            'active' => $this->active,
            'modelUrl' => $this->modelUrl,
        ];
    }
}
