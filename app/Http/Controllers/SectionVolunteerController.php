<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SectionVolunteer;
use App\Models\EventSection;

class SectionVolunteerController extends Controller
{
    /**
     * Join a section (sectioned/mixed).
     */

    public function join(Request $request, EventSection $section)
    {
        $event = $section->event;

        if ($event->type !== 'sectioned') {
            return response()->json(['error' => 'This event does not have sections'], 400);
        }

        if ($section->capacity !== null && $section->volunteers()->count() >= $section->capacity) {
            return response()->json(['error' => 'Section is full'], 400);
        }

        $volunteer = SectionVolunteer::firstOrCreate(
            [
                'event_section_id' => $section->id,
                'user_id' => $request->user()->id,
            ],
            [
                'joined_at' => now(),
            ]
        );

        return response()->json([
            'message' => $volunteer->wasRecentlyCreated ? 'Joined section' : 'Already joined',
            'section_volunteer' => $volunteer,
        ]);
    }
}
