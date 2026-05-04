<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;
use Illuminate\Notifications\Messages\MailMessage;

class EventChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public Event $event,
        public string $changeType = 'updated'
    )
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Event ' . $this->changeType . ': ' . $this->event->title)
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('The event "' . $this->event->title . '" has been ' . $this->changeType . '.')
            ->line('Start date: ' . $this->event->start_date->format('d.m.Y H:i'))
            ->line('Location: ' . ($this->event->location ?? 'Not specified'))
            ->action('View event', route('events.show', $this->event));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'event_' . $this->changeType,
            'title' => 'Event ' . ucfirst($this->changeType),
            'message' => '"' . $this->event->title . '" has been ' . $this->changeType . '.',
            'url' => route('events.show', $this->event),
            'event_id' => $this->event->id,
        ];
    }
}