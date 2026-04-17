<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\EventApplication;

use Illuminate\Http\Request;

class EventApplicationController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $user = $request->user();

        //Prevent duplicate applications
        if (EventApplication::where('event_id', $event->id)
            ->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'You have already applied for this event.');
        }

        //prevent applying to full events
        if ($event->isFull()) {
            return redirect()->back()->with('error', 'This event is already full.');
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

        //create application
        EventApplication::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'message' => $validated['message'] ?? null,
            'cv_path' => $validated['cv_path'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your application has been submitted successfully.');
        
    }

    public function index(Event $event)
    {
        // only event owner
        abort_if($event->organizer_id !== auth()->id(), 403);

        $applications = $event->applications()->with('user')->latest()->get();

        return view('applications.index', compact('event', 'applications'));
    }

    public function approve(EventApplication $application)
    {
        $event = $application->event;

        abort_if($event->organizer_id !== auth()->id(), 403);

        $application->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Application approved.');
    }

    public function reject(EventApplication $application)
    {
        $event = $application->event;

        abort_if($event->organizer_id !== auth()->id(), 403);

        $application->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Application rejected.');
    }

    public function withdraw(EventApplication $application)
    {
        // Only the owner (volunteer) can withdraw own application
        abort_if($application->user_id !== auth()->id(), 403);

        // Only allow if still pending
        if (!$application->isPending()) {
            return back()->with('error', 'You can only withdraw pending applications.');
        }

        // Update status to cancelled
        $application->update([
            'status' => EventApplication::STATUS_CANCELLED,
        ]);

        return back()->with('success', 'Application withdrawn successfully.');
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
