<?php

namespace App\Library\Services\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailActiveAccount extends Notification implements ShouldQueue
{
    use Queueable;

    protected $params;

    /**
     * Create a new job instance.
     *
     * @param array $params
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Get the notification channels.
     *
     * @param  mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[EAS HCM - Blog] Active your account!')
            ->greeting('Hello!')
            ->line('You are registered account on EAS HCM - Blog.')
            ->line('You must active account if you want to using it.')
            ->action('Click to active.', $this->params['url']);
    }
}
