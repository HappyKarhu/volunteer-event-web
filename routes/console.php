<?php

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $events = Event::query()
        ->where('status', 'published')
        ->whereNull('reminder_sent_at')
        ->whereBetween('start_date', [
            now(),
            now()->addDay(),
        ])
        ->with('approvedApplications.user')
        ->get();

    foreach ($events as $event) {
        $event->approvedApplications
            ->pluck('user')
            ->filter()
            ->each(function ($user) use ($event) {
                $user->notify(new EventReminderNotification($event));
            });

        $event->update([
            'reminder_sent_at' => now(),
        ]);
    }
})->everyThirtyMinutes();
