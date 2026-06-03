<?php

use App\Models\Event;
use App\Models\EventApplication;
use App\Models\User;
use App\Notifications\ApplicationStatusChangeNotification;
use Illuminate\Support\Facades\Notification;

function makeSimpleEvent(User $organizer, array $overrides = []): Event
{
    return Event::factory()->create(array_merge([
        'organizer_id' => $organizer->id,
        'type' => 'simple',
        'capacity' => 5,
        'status' => 'published',
    ], $overrides));
}

test('volunteer applies to event', function () {
    $organizer = User::factory()->create(['role' => 'organizer']);
    $volunteer = User::factory()->create(['role' => 'volunteer']);
    $event = makeSimpleEvent($organizer);

    $this->actingAs($volunteer)
        ->from(route('events.show', $event))
        ->post(route('events.apply', $event), [
            'message' => 'I would love to help.',
        ])
        ->assertRedirect(route('events.show', $event));

    $this->assertDatabaseHas('event_applications', [
        'event_id' => $event->id,
        'user_id' => $volunteer->id,
        'message' => 'I would love to help.',
        'status' => EventApplication::STATUS_PENDING,
    ]);
});

test('organizer approves application', function () {
    Notification::fake();

    $organizer = User::factory()->create(['role' => 'organizer']);
    $volunteer = User::factory()->create(['role' => 'volunteer']);
    $event = makeSimpleEvent($organizer);
    $application = EventApplication::create([
        'event_id' => $event->id,
        'user_id' => $volunteer->id,
        'status' => EventApplication::STATUS_PENDING,
    ]);

    $this->actingAs($organizer)
        ->from(route('events.applications', $event))
        ->post(route('applications.approve', $application))
        ->assertRedirect(route('events.applications', $event));

    $this->assertDatabaseHas('event_applications', [
        'id' => $application->id,
        'status' => EventApplication::STATUS_APPROVED,
    ]);

    $this->assertDatabaseHas('event_attendees', [
        'event_id' => $event->id,
        'user_id' => $volunteer->id,
    ]);

    Notification::assertSentTo(
        $volunteer,
        ApplicationStatusChangeNotification::class,
        fn (ApplicationStatusChangeNotification $notification) =>
            $notification->application->id === $application->id
            && $notification->application->status === EventApplication::STATUS_APPROVED
    );
});

test('organizer rejects application', function () {
    Notification::fake();

    $organizer = User::factory()->create(['role' => 'organizer']);
    $volunteer = User::factory()->create(['role' => 'volunteer']);
    $event = makeSimpleEvent($organizer);
    $application = EventApplication::create([
        'event_id' => $event->id,
        'user_id' => $volunteer->id,
        'status' => EventApplication::STATUS_PENDING,
    ]);

    $this->actingAs($organizer)
        ->from(route('events.applications', $event))
        ->post(route('applications.reject', $application))
        ->assertRedirect(route('events.applications', $event));

    $this->assertDatabaseHas('event_applications', [
        'id' => $application->id,
        'status' => EventApplication::STATUS_REJECTED,
    ]);

    $this->assertDatabaseMissing('event_attendees', [
        'event_id' => $event->id,
        'user_id' => $volunteer->id,
    ]);

    Notification::assertSentTo(
        $volunteer,
        ApplicationStatusChangeNotification::class,
        fn (ApplicationStatusChangeNotification $notification) =>
            $notification->application->id === $application->id
            && $notification->application->status === EventApplication::STATUS_REJECTED
    );
});

test('event full puts next volunteer on waitlist', function () {
    $organizer = User::factory()->create(['role' => 'organizer']);
    $volunteerA = User::factory()->create(['role' => 'volunteer']);
    $volunteerB = User::factory()->create(['role' => 'volunteer']);
    $event = makeSimpleEvent($organizer, ['capacity' => 1]);
    $applicationA = EventApplication::create([
        'event_id' => $event->id,
        'user_id' => $volunteerA->id,
        'status' => EventApplication::STATUS_PENDING,
    ]);

    $this->actingAs($organizer)
        ->post(route('applications.approve', $applicationA));

    $this->actingAs($volunteerB)
        ->from(route('events.show', $event))
        ->post(route('events.apply', $event))
        ->assertRedirect(route('events.show', $event));

    $this->assertDatabaseHas('event_applications', [
        'event_id' => $event->id,
        'user_id' => $volunteerA->id,
        'status' => EventApplication::STATUS_APPROVED,
    ]);

    $this->assertDatabaseHas('event_attendees', [
        'event_id' => $event->id,
        'user_id' => $volunteerA->id,
    ]);

    $this->assertDatabaseHas('event_applications', [
        'event_id' => $event->id,
        'user_id' => $volunteerB->id,
        'status' => EventApplication::STATUS_WAITLISTED,
    ]);
});

test('volunteer cannot edit organizer event', function () {
    $organizer = User::factory()->create(['role' => 'organizer']);
    $volunteer = User::factory()->create(['role' => 'volunteer']);
    $event = makeSimpleEvent($organizer);

    $this->actingAs($volunteer)
        ->get(route('events.edit', $event))
        ->assertForbidden();
});
