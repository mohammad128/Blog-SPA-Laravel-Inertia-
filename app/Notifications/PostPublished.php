<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostPublished extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
//        return ['mail', 'database'];
        return [ 'database'];
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
                    ->line('New Post...')
                    ->line($this->post->title)
                    ->action('Read Post..', route('Post.show', ['post'=>$this->post->slug]));
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
            'id' => $this->post->id,
            'title' => $this->post->title,
            'created_at' => $this->post->created_at,
            'slug' => $this->post->slug,
            'created_at_for_human' => $this->post->created_at_for_human,
            'url' => $this->post->url,
            'feature_image' => $this->post->feature_image,
        ];
    }
}
