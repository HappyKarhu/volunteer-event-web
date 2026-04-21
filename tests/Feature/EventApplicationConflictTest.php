<?php

use App\Models\Event;
use App\Models\EventApplication;
use App\Models\User;

it('prevents volunteers from applying to overlapping events on the same day', function () {
    $volunteer = User::factory()->volunteer()->create();
    $organizer = User::factory()->organizer()->create();

    $firstEvent = Event::factory()->create([
        'organizer_id' => $organizer->id,
        'type' => 'simple',
        'status' => 'published',
        'start_date' => now()->addDays(5)->setTime(9, 0),
        'end_date' => now()->addDays(5)->setTime(12, 0),
    ]);

    $secondEvent = Event::factory()->create([
        'organizer_id' => $organizer->id,
        'type' => 'simple',
        'status' => 'published',
        'start_date' => now()->addDays(5)->setTime(15, 0),
        'end_date' => now()->addDays(5)->setTime(18, 0),
    ]);

    EventApplication::create([
        'event_id' => $firstEvent->id,
        'user_id' => $volunteer->id,
        'status' => EventApplication::STATUS_PENDING,
    ]);

    $response = $this->actingAs($volunteer)->post(route('events.apply', $secondEvent), [
        'message' => 'I would love to help.',
    ]);

    $response->assertSessionHas('error');
    expect(EventApplication::where('user_id', $volunteer->id)->count())->toBe(1);
});
