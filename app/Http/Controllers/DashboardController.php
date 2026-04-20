<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isOrganizer()) {
            // Organizer: show their events
            $events = $user->events()->latest()->get();
            return view('dashboard.organizer', compact('user', 'events'));
        }

        if ($user->isVolunteer()) {
            // Volunteer: show applied events or saved events
            $applications = $user->applications()
                ->with(['event.organizer', 'statusHistory.user']) // Eager load event, organizer, and status timeline
                ->latest()
                ->get();
            $savedEvents = $user->savedEvents()->latest()->get();
            return view('dashboard.volunteer', compact('user', 'applications', 'savedEvents'));
        }

        // Default fallback
        return view('dashboard', compact('user'));
    }
}
