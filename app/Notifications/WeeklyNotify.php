<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyNotify extends Notification
{
    use Queueable;
    private $sharedPostsCount;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sharedPostsCount)
    {
        $this->sharedPostsCount = $sharedPostsCount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->greeting('Hey there!')
            ->line('This is your weekly digest from ' . env('APP_NAME'))
            ->line('We shared ' . $this->sharedPostsCount['count'] . ' social media posts on your accounts this week.')
            ->line("Thanks for using " . env('APP_NAME'))
            ->line("Cheers,");
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
            //
        ];
    }
}
