<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSection;
use Illuminate\Database\Seeder;

class EventSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::where('type', 'sectioned')->first();

        if (!$event) {
            throw new \Exception('No sectioned event found. Check EventSeeder.');
        }

        EventSection::create([
            'event_id' => $event->id,
            'role_name' => 'Registration',
            'description' => 'Handle attendee check-in and information',
            'capacity' => 5,
        ]);

        EventSection::create([
            'event_id' => $event->id,
            'role_name' => 'Logistics',
            'description' => 'Manage event logistics and coordination',
            'capacity' => 2,
        ]);
    }
}