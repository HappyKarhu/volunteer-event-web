<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isOrganizer()) {
            // Organizer: show their events
            $events = $user->events()
                ->with('applications')
                ->orderBy('start_date')
                ->get();
            $calendar = $this->buildCalendarData($events, 'Your event schedule');

            return view('dashboard.organizer', compact('user', 'events', 'calendar'));
        }

        if ($user->isVolunteer()) {
            // Volunteer: show applied events or saved events
            $applications = $user->applications()
                ->with(['event.organizer', 'event.applications', 'statusHistory.user']) // Eager load event, organizer, and status timeline
                ->latest()
                ->get();
            $savedEvents = $user->savedEvents()
                ->with('applications')
                ->orderBy('start_date')
                ->get();

            $calendarEvents = $applications
                ->map(function ($application) {
                    $event = $application->event;

                    if ($event) {
                        $event->setAttribute('calendar_context', [
                            'label' => $application->status_label,
                            'tone' => match ($application->status) {
                                'approved' => 'emerald',
                                'pending' => 'amber',
                                'waitlisted' => 'indigo',
                                'rejected' => 'red',
                                default => 'slate',
                            },
                        ]);
                    }

                    return $event;
                })
                ->filter()
                ->merge(
                    $savedEvents
                        ->whereNotIn('id', $applications->pluck('event_id'))
                        ->map(function ($event) {
                            $event->setAttribute('calendar_context', [
                                'label' => 'Saved',
                                'tone' => 'sky',
                            ]);

                            return $event;
                        })
                )
                ->values();

            $calendar = $this->buildCalendarData($calendarEvents, 'Your volunteer schedule');

            return view('dashboard.volunteer', compact('user', 'applications', 'savedEvents', 'calendar'));
        }

        // Default fallback
        return view('dashboard', compact('user'));
    }

    private function buildCalendarData(Collection $events, string $heading): array
    {
        $month = now()->startOfMonth();
        $gridStart = $month->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $days = [];

        for ($date = $gridStart->copy(); $date->lte($gridEnd); $date->addDay()) {
            $dayEvents = $events
                ->filter(function ($event) use ($date) {
                    return $event->start_date && $event->end_date
                        && $event->start_date->copy()->startOfDay()->lte($date)
                        && $event->end_date->copy()->startOfDay()->gte($date);
                })
                ->sortBy('start_date')
                ->values();

            $days[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $month->month,
                'isToday' => $date->isToday(),
                'events' => $dayEvents,
            ];
        }

        return [
            'heading' => $heading,
            'monthLabel' => $month->format('F Y'),
            'daysOfWeek' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'days' => $days,
            'upcomingEvents' => $events
                ->filter(fn ($event) => $event->end_date && $event->end_date->isFuture())
                ->sortBy('start_date')
                ->take(4)
                ->values(),
        ];
    }
}
