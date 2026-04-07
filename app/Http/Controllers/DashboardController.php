<?php

namespace App\Http\Controllers;

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
            $appliedEvents = $user->attendedEvents()->latest()->get();
            $savedEvents = $user->savedEvents()->latest()->get();
            return view('dashboard.volunteer', compact('user', 'appliedEvents', 'savedEvents'));
        }

        // Default fallback
        return view('dashboard', compact('user'));
    }
}