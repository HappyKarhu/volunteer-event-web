<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Event $event
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Reminder: ' . $this->event->title . ' starts soon')
                    ->greeting('Hi ' . $notifiable->name . ',')
                    ->line('This is a reminder that "' . $this->event->title . '" starts soon.')
                    ->line('Start: ' . $this->event->start_date->format('d.m.Y H:i'))
                    ->line('Location: ' . ($this->event->location ?? 'Not specified'))
                    ->action('View event', route('events.show', $this->event));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'event_reminder',
            'title' => 'Event reminder',
            'message' => '"' . $this->event->title . '" starts soon.',
            'url' => route('events.show', $this->event),
            'event_id' => $this->event->id,
        ];
    }
}
