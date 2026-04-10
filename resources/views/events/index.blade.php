<x-layout>
    <h1 class="text-2xl font-bold mb-4">{{ $title ?? 'All Events' }}</h1>
    <p>For more information about each event, click on the details.</p><br>

    <form method="GET" action="{{ route('events.index') }}" class="mb-8 rounded-2xl border border-gray-100 bg-gray-50 p-5 shadow-sm">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
            <div>
                <label for="search" class="mb-1 block text-sm font-medium text-gray-700">Search</label>
                <input
                    id="search"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Title, description, tags..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
            </div>

            <div>
                <label for="status" class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
                    <option value="published" {{ request('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label for="date" class="mb-1 block text-sm font-medium text-gray-700">Date</label>
                <select
                    id="date"
                    name="date"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
                    <option value="">Any time</option>
                    <option value="upcoming" {{ request('date') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="this_month" {{ request('date') === 'this_month' ? 'selected' : '' }}>This month</option>
                    <option value="past" {{ request('date') === 'past' ? 'selected' : '' }}>Past</option>
                </select>
            </div>

            <div>
                <label for="location" class="mb-1 block text-sm font-medium text-gray-700">Location</label>
                <input
                    id="location"
                    type="text"
                    name="location"
                    value="{{ request('location') }}"
                    placeholder="City or area"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
            </div>

            <div class="flex flex-col justify-end gap-3">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input
                        type="checkbox"
                        name="free_only"
                        value="1"
                        {{ request()->boolean('free_only') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                    >
                    Free only
                </label>

                <div class="flex gap-2">
                    </button>
                    <button type="submit" class="flex items-center gap-2 bg-emerald-600 hover:bg-amber-500 text-white py-2 px-4 rounded text-sm transition-colors duration-200 transform hover:scale-105">
                        <i class="fa-solid fa-sliders"></i>
                        Filter
                    </button>

                    <a href="{{ route('events.index') }}" class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-white px-4 rounded text-sm font-semibold text-emerald-600 shadow-sm transition hover:bg-emerald-50">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </form>

    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($events as $event)
            <li>
                <x-event-card :event="$event" />
            </li>
        @empty
            <li>No Events Found</li>
        @endforelse
    </ul>

    <div class="mt-8">
        {{ $events->links('vendor.pagination.emerald') }}
    </div>
</x-layout>
