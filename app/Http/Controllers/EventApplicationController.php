<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\EventApplication;
use App\Models\EventAttendee;
use App\Models\SectionVolunteer;
use Illuminate\Http\Request;

class EventApplicationController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $user = $request->user();

        if ($user->role !== 'volunteer') {
            abort(403, 'Only volunteers can apply for events.');
        }

        //Prevent duplicate applications
        if (EventApplication::where('event_id', $event->id)
            ->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'You have already applied for this event.');
        }

        $conflictingApplication = EventApplication::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                EventApplication::STATUS_PENDING,
                EventApplication::STATUS_APPROVED,
                EventApplication::STATUS_WAITLISTED,
            ])
            ->where('event_id', '!=', $event->id)
            ->whereHas('event', function ($query) use ($event) {
                $query->whereDate('start_date', '<=', $event->end_date->toDateString())
                    ->whereDate('end_date', '>=', $event->start_date->toDateString());
            })
            ->with('event')
            ->first();

        if ($conflictingApplication) {
            return redirect()->back()->with(
                'error',
                'You already have an active application for on the same day. Withdraw it first before applying to another event on that date.'
            );
        }

        //validate -pdf only, max 2MB
        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        //upload CV if provided
        if ($request->hasFile('cv')) {
            $validated['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        //create application, using waitlist if simple event is already full
        $status = $event->type === 'simple' && $event->isFull()
            ? EventApplication::STATUS_WAITLISTED
            : EventApplication::STATUS_PENDING;

        EventApplication::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'message' => $validated['message'] ?? null,
            'cv_path' => $validated['cv_path'] ?? null,
            'status' => $status,
        ]);

        $message = $status === EventApplication::STATUS_WAITLISTED
            ? 'Event is full. You have been added to the waitlist.'
            : 'Your application has been submitted successfully.';

        return redirect()->back()->with('success', $message);
        
    }

    public function index(Event $event)
    {
        // only event owner
        abort_if($event->organizer_id !== auth()->id(), 403);

        $event->load('sections.volunteers');
        $applications = $event->applications()->with('user')->latest()->get();

        return view('applications.index', compact('event', 'applications'));
    }

    public function approve(Request $request, EventApplication $application)
    {
        $event = $application->event;

        abort_if($event->organizer_id !== auth()->id(), 403);

        if ($application->status === EventApplication::STATUS_APPROVED) {
            return back()->with('info', 'Application is already approved.');
        }

        if ($event->type === 'simple') {
            if ($event->isFull()) {
                return back()->with('error', 'This event is already full.');
            }

            EventAttendee::firstOrCreate(
                [
                    'event_id' => $event->id,
                    'user_id' => $application->user_id,
                ],
                [
                    'joined_at' => now(),
                ]
            );
        }

        if ($event->type === 'sectioned') {
            $validated = $request->validate([
                'event_section_id' => ['required', 'exists:event_sections,id'],
            ]);

            $section = $event->sections()->findOrFail($validated['event_section_id']);

            if ($section->capacity !== null && $section->volunteers()->count() >= $section->capacity) {
                return back()->with('error', 'Selected section is already full.');
            }

            SectionVolunteer::firstOrCreate(
                [
                    'event_section_id' => $section->id,
                    'user_id' => $application->user_id,
                ],
                [
                    'joined_at' => now(),
                ]
            );
        }

        $application->update([
            'status' => EventApplication::STATUS_APPROVED,
        ]);

        return back()->with('success', 'Application approved.');
    }

    public function reject(EventApplication $application)
    {
        $event = $application->event;

        abort_if($event->organizer_id !== auth()->id(), 403);

        if ($application->status === EventApplication::STATUS_REJECTED) {
            return back()->with('info', 'Application is already rejected.');
        }

        $application->update([
            'status' => EventApplication::STATUS_REJECTED,
        ]);

        return back()->with('success', 'Application rejected.');
    }

    public function withdraw(EventApplication $application)
    {
        // Only the owner (volunteer) can withdraw own application
        abort_if($application->user_id !== auth()->id(), 403);

        $event = $application->event;

        if (!in_array($application->status, [
            EventApplication::STATUS_PENDING,
            EventApplication::STATUS_WAITLISTED,
            EventApplication::STATUS_APPROVED,
        ], true)) {
            return back()->with('error', 'You can only withdraw pending, waitlisted, or approved applications.');
        }

        // if approved remove from attendees/volunteers
        if ($application->isApproved()) {
            if ($event->type === 'simple') {
                EventAttendee::where('event_id', $event->id)
                    ->where('user_id', $application->user_id)
                    ->delete();
            }
        
            //sectioned event - remove from volunteers
            if ($event->type === 'sectioned') {
                SectionVolunteer::where('user_id', $application->user_id)
                    ->whereHas('section', function ($q) use ($event) {
                        $q->where('event_id', $event->id);
                    })
                    ->delete();
            }
        }

        // Update status to cancelled
        $application->update([
            'status' => EventApplication::STATUS_CANCELLED,
        ]);

        if ($event->type === 'simple') {
            $this->promoteFromWaitlist($event);
        }

        return back()->with('success', 'Application withdrawn successfully.');
    }

    //autopromote from waitlist when a spot opens up
    private function promoteFromWaitlist(Event $event): void
    {
        //only for simple events (because sectioned events require organizer approval for section assignment)
        if ($event->type !== 'simple') {
        return;
        }

        if ($event->isFull()) {
            return;
        }

        $next = EventApplication::where('event_id', $event->id)
            ->where('status', EventApplication::STATUS_WAITLISTED)
            ->oldest()
            ->first();

        if (!$next) {
            return;
        }

        $next->update([
            'status' => EventApplication::STATUS_APPROVED,
        ]);

        EventAttendee::firstOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => $next->user_id,
            ],
            [
                'joined_at' => now(),
            ]
        );
    }


    public function show(EventApplication $application)
    {
        $user = auth()->user();

        abort_unless(
            $user->id === $application->user_id ||
            $user->id === $application->event->organizer_id,
            403
        );

        $application->load(['messages.sender', 'messages.receiver', 'user', 'event']);

        return view('applications.show', compact('application'));
    }
}
