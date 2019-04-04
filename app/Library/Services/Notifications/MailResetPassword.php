<?php

namespace App\Library\Services\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailResetPassword extends Notification implements ShouldQueue
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
            ->subject('[EAS HCM - Blog] Reset your password!')
            ->greeting('Hello!')
            ->line('You recently requested a password reset for your account. To complete the process, click on the link below.')
            ->action('Reset password link.', $this->params['url'])
            ->line('If you did not make this request, it\'s possible that someone else mistakenly entered your email address and your account is still secure. If you believe someone has accessed your account, you should change your password immediately.');
    }
}
