<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\EventAttendee;

class EventController extends Controller
{
    /**
     * @desc Show all events with optional filters (e.g., by date, location, type)
     * @route GET /events
     */

    public function index(): View
    {
        $title = 'Available Events';
        $status = request('status', 'published');
        $date = request('date');
        $search = trim((string) request('search'));
        $location = trim((string) request('location'));
        $freeOnly = request()->boolean('free_only');

        $events = Event::query()
            ->when(in_array($status, ['published', 'cancelled'], true), function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->where('status', 'published');
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('tags', 'like', "%{$search}%");
                });
            })
            ->when($location !== '', function ($query) use ($location) {
                $query->where('location', 'like', "%{$location}%");
            })
            ->when($freeOnly, function ($query) {
                $query->where('is_free', true);
            })
            ->when($date === 'upcoming', function ($query) {
                $query->whereDate('end_date', '>=', now());
            })
            ->when($date === 'past', function ($query) {
                $query->whereDate('end_date', '<', now());
            })
            ->when($date === 'this_month', function ($query) {
                $query->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()]);
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('events.index', compact('events', 'title'));
    }

    /**
     * @desc Show create event form
     * @route GET /events/create
     */
    public function create(): View
    {
        return view('events.create');
    }

    /**
     * @desc Store a new event in the database
     * @route POST /events
     */
        
    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->isOrganizer()) {
            abort(403, 'Only organizers can create events');
        }

        $validated = $this->validateEvent($request);
        $validated = $this->prepareEventData($request, $validated);

        $event = Event::create([
            ...$validated,
            'organizer_id' => auth()->id(),
        ]);

        $this->syncSections($event, $validated['type'], $request->input('sections', []));

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * @desc Display the specified event
     * @route GET /events/{id}
     */
    public function show(Event $event): View
    {
        $canApply = auth()->check() && auth()->user()->role === 'volunteer';
        $event->load(['sections.volunteers']);

        return view('events.show', compact('event', 'canApply'));
    }

    /**
     * @desc Show the form for editing the specified event
     * @route GET /events/{id}/edit
     */
    public function edit(Event $event): View
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'You do not own this event');
        }

        $event->load('sections');

        return view('events.edit', compact('event'));
    }

    // @desc   Save a job to favorites
    // @route  POST /jobs/{id}/save
    public function save(Event $event): string
    {
        return 'Save Event';
    }

    /**
     * @desc Update the specified event in the database
     * @route PUT /events/{id}
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'You do not own this event');
        }

        $validated = $this->validateEvent($request);
        $validated = $this->prepareEventData($request, $validated, $event);

        $event->update($validated);
        $this->syncSections($event, $validated['type'], $request->input('sections', []));

        return redirect()->route('dashboard')->with('success', 'Event updated successfully.');
    }
    /**
     * @desc Apply to the specified event
     * @route 
     */
     public function apply(Request $request, Event $event)
    {
        $user = $request->user();
        // Only volunteers
        if ($user->role !== 'volunteer') {
            abort(403, 'Only volunteers can apply');
        }

        // Prevent duplicate join
        if ($event->applications()->where('user_id', $user->id)->exists()) {
        return back()->with('error', 'You already applied for this event.');
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $cvPath = null;

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        $event->applications()->create([
            'user_id' => $user->id,
            'message' => $validated['message'] ?? null,
            'cv_path' => $cvPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Application submitted!');

        }

    
    /**
     * @desc Remove the specified event from storage
     * @route DELETE /events/{id}
     */
    public function destroy(Event $event): RedirectResponse
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'You do not own this event');
        }

        $event->delete();

        return redirect()->route('dashboard')->with('success', 'Event deleted successfully.');
    }

    private function validateEvent(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:simple,sectioned',
            'photo' => 'nullable|image|max:5120',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'tags' => 'nullable|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'bring_wear' => 'nullable|string',
            'is_free' => 'required|boolean',
            'price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->boolean('is_free') && is_null($value)) {
                        $fail('Price is required when event is not free.');
                    }
                },
            ],
            'status' => 'required|in:draft,published,cancelled',
            'sections' => 'nullable|array',
            'sections.*.role_name' => 'nullable|string|max:255',
            'sections.*.description' => 'nullable|string|max:1000',
            'sections.*.capacity' => 'nullable|integer|min:1',
        ]);

        $sections = $this->normalizeSections($request->input('sections', []));

        if (($validated['type'] ?? null) === 'sectioned' && empty($sections)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'sections' => 'Add at least one role for a sectioned event.',
            ]);
        }

        return $validated;
    }

    private function prepareEventData(Request $request, array $validated, ?Event $event = null): array
    {
        if (!empty($validated['tags'])) {
            $tags = explode(',', $validated['tags']);
            $tags = array_map(fn ($tag) => trim($tag), $tags);
            $tags = array_filter($tags);
            $validated['tags'] = implode(',', $tags);
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $validated['photo'] = $request->file('photo')->store('events', 'public');
        } elseif (!$event?->photo) {
            $validated['photo'] = 'events/volunteerio-default.png';
        }

        if ($validated['is_free']) {
            $validated['price'] = null;
        }

        if ($validated['type'] === 'sectioned') {
            $validated['capacity'] = null;
        }

        return $validated;
    }

    private function syncSections(Event $event, string $type, array $sections): void
    {
        if ($type !== 'sectioned') {
            $event->sections()->delete();
            return;
        }

        $normalizedSections = $this->normalizeSections($sections);
        $existingIds = [];

        foreach ($normalizedSections as $section) {
            $model = $event->sections()->updateOrCreate(
                [
                    'id' => $section['id'] ?? null,
                ],
                [
                    'role_name' => $section['role_name'],
                    'description' => $section['description'] ?? null,
                    'capacity' => $section['capacity'] ?? null,
                ]
            );

            $existingIds[] = $model->id;
        }

        $event->sections()->whereNotIn('id', $existingIds)->delete();
    }

    private function normalizeSections(array $sections): array
    {
        return collect($sections)
            ->map(function ($section) {
                return [
                    'id' => $section['id'] ?? null,
                    'role_name' => trim((string) ($section['role_name'] ?? '')),
                    'description' => trim((string) ($section['description'] ?? '')),
                    'capacity' => $section['capacity'] === '' ? null : $section['capacity'],
                ];
            })
            ->filter(fn ($section) => $section['role_name'] !== '')
            ->values()
            ->all();
    }
}
