<x-layout>
    <div class="container mx-auto mt-10 px-5">
        <h2 class="mt-6 mb-4 text-2xl font-semibold">Recent Upcoming Events</h2>

        @if($events->isEmpty())
            <p>No events available at the moment.</p>
        @else
            <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                @foreach($events->take(6) as $event) {{-- Show only the 6 most recent events --}}
                    <x-event-card :event="$event" />
                @endforeach
            </div>
        @endif
    </div>

    <x-bottom-banner />
</x-layout>