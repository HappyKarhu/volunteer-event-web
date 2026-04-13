<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
                            if (!$request->is_free && is_null($value)) {
                                $fail('Price is required when event is not free.');
                            }
                        },
                    ],
        'status' => 'required|in:draft,published,cancelled',
    ]);

    // Clean tags
    if (!empty($validated['tags'])) {
        $tags = explode(',', $validated['tags']);
        $tags = array_map(fn($tag) => trim($tag), $tags);
        $tags = array_filter($tags);
        $validated['tags'] = implode(',', $tags);
    }

    // Handle photo upload
    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        $path = $request->file('photo')->store('events', 'public');
        $validated['photo'] = $path;
    } else {
        $validated['photo'] = 'events/volunteerio-default.png';
    }

    // Enforce FREE vs PRICE
    if ($validated['is_free']) {
        $validated['price'] = null;
    }

    // Enforce type logic (sectioned events don't use capacity)
    if ($validated['type'] === 'sectioned') {
        $validated['capacity'] = null;
    }

    $event = Event::create([
        ...$validated,
        'organizer_id' => auth()->id(),
    ]);

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
                                if (!$request->is_free && is_null($value)) {
                                    $fail('Price is required when event is not free.');
                                }
                            },
                        ],
            'status' => 'required|in:draft,published,cancelled',
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('events', 'public');
            $validated['photo'] = $path;
        } elseif (!$event->photo) {
            $validated['photo'] = 'events/volunteerio-default.png';
        }

        if ($validated['is_free']) {
            $validated['price'] = null;
        }

        if ($validated['type'] === 'sectioned') {
            $validated['capacity'] = null;
        }

        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'You do not own this event');
        }

        $event->update($validated);

        return redirect()->route('dashboard')->with('success', 'Event updated successfully.');
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
    
}