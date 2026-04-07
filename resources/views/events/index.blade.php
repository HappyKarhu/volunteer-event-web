<x-layout>
    <h1 class="text-2xl font-bold mb-4">{{ $title ?? 'All Events' }}</h1>
    <p>For more information about each event, click on the details.</p><br>

    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($events as $event)
        <li>
            <x-event-card :event="$event" />
        </li>
    @empty
        <li>No Events Found</li>
    @endforelse
</ul>
</x-layout>