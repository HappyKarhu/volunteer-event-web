<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Volunteer Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Applied Events --}}
            <h3 class="text-lg font-semibold mb-2">Applied Events</h3>
            @if($appliedEvents->count())
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($appliedEvents as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You haven't applied to any events yet.</p>
            @endif

            {{-- Saved Events --}}
            <h3 class="text-lg font-semibold mt-6 mb-2">Saved Events</h3>
            @if($savedEvents->count())
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($savedEvents as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">You have no saved events.</p>
            @endif
        </div>
    </div>
</x-app-layout>