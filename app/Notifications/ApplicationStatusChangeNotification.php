<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\EventApplication;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationStatusChangeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public EventApplication $application
    )
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $event = $this->application->event;

        return (new MailMessage)
                    ->subject('Your application was ' . $this->application->status)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your application for "' . $event->title . '" was ' . $this->application->status . '.')
                    ->action('View application', route('applications.show', $this->application))
                    ->line('Thank you for using Volunteerio.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_status_changed',
            'title' => 'Application ' . ucfirst($this->application->status),
            'message' => 'Your application for "' . $this->application->event->title . '" was ' . $this->application->status . '.',
            'url' => route('applications.show', $this->application),
            'application_id' => $this->application->id,
            'event_id' => $this->application->event_id,
        ];
    }

}