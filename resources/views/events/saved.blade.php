<x-layout>
    <div class="container mx-auto mt-10 px-5">
        <h2 class="text-2xl font-semibold mb-6">Saved Events</h2>

        @if($bookmarks->count())
            <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
                @foreach($bookmarks as $event)
                    <x-event-card :event="$event" />
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $bookmarks->links() }}
            </div>
        @else
            <p class="text-gray-500">You have no saved events yet.</p>
        @endif
    </div>

    <x-bottom-banner />
</x-layout>