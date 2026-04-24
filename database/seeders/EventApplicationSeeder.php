<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\EventApplication;
use App\Models\EventAttendee;
use App\Models\EventSection;
use App\Models\SectionVolunteer;

class EventApplicationSeeder extends Seeder
{
    public function run(): void
    {
        // Get users
        $volunteer1 = User::where('email', 'volunteer1@test.com')->first();
        $volunteer2 = User::where('email', 'volunteer2@test.com')->first();

        // Get events
        $simpleEvent = Event::where('type', 'simple')->first();
        $sectionedEvent = Event::where('type', 'sectioned')->first();

        // =========================
        // APPROVED APPLICATION
        // =========================
        $approvedApp = EventApplication::create([
            'event_id' => $simpleEvent->id,
            'user_id' => $volunteer1->id,
            'message' => 'I would love to help!',
            'status' => 'approved',
        ]);

        // Add to attendees
        EventAttendee::create([
            'event_id' => $simpleEvent->id,
            'user_id' => $volunteer1->id,
        ]);

        // =========================
        // PENDING APPLICATION
        // =========================
        EventApplication::create([
            'event_id' => $simpleEvent->id,
            'user_id' => $volunteer2->id,
            'message' => 'Sounds interesting I am in',
            'status' => 'pending',
        ]);

        // =========================
        // REJECTED APPLICATION (sectioned)
        // =========================
        EventApplication::create([
            'event_id' => $sectionedEvent->id,
            'user_id' => $volunteer2->id,
            'message' => 'I want to join this event, be a chef!',
            'status' => 'rejected',
        ]);

        // =========================
        // SECTION ASSIGNMENT
        // =========================
        $section = EventSection::where('event_id', $sectionedEvent->id)->first();

        if ($section) {
            SectionVolunteer::create([
                'event_section_id' => $section->id,
                'user_id' => $volunteer1->id,
            ]);
        }
    }
}