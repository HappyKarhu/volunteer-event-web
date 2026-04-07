<x-layout>
    <div class="container mx-auto mt-10 px-5">
        {{-- Event Header --}}
        <div class="flex flex-col md:flex-row gap-6 mb-6">
            @if($event->photo)
                <img src="{{ asset('images/events/' . $event->photo) }}" 
                     alt="{{ $event->title }}" 
                     class="w-full md:w-1/3 h-64 object-cover rounded shadow">
            @endif

            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-3">{{ $event->title }}</h1>
                
                <p class="text-gray-600 mb-2">
                    <strong>Date:</strong> {{ $event->start_date->format('M d, Y H:i') }} 
                    - {{ $event->end_date->format('M d, Y H:i') }}
                </p>

                <p class="text-gray-600 mb-2">
                    <strong>Location:</strong> {{ $event->location ?? 'TBD' }}
                </p>

                <p class="text-gray-600 mb-2">
                    <strong>Price:</strong>
                    @if($event->is_free)
                        <span class="text-emerald-600 font-semibold">Free</span>
                    @else
                        <span class="text-amber-600 font-semibold">${{ number_format($event->price, 2) }}</span>
                    @endif
                </p>

                <p class="text-gray-600 mb-2">
                    <strong>Capacity:</strong> {{ $event->capacity ?? 'Unlimited' }} |
                    <strong>Participants:</strong> {{ $event->participantCount() }}
                </p>
            </div>
        </div>

        {{-- Event Description --}}
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-2">Description</h2>
            <p class="text-gray-700">{{ $event->description }}</p>
        </div>

        @if($event->responsibilities)
            <div class="mb-6">
                <h2 class="text-2xl font-semibold mb-2">Responsibilities</h2>
                <p class="text-gray-700">{{ $event->responsibilities }}</p>
            </div>
        @endif

        @if($event->bring_wear)
            <div class="mb-6">
                <h2 class="text-2xl font-semibold mb-2">What to Bring / Wear</h2>
                <p class="text-gray-700">{{ $event->bring_wear }}</p>
            </div>
        @endif

        {{-- Requirements --}}
        @if($event->requirements)
            <div class="mb-6">
                <h2 class="text-2xl font-semibold mb-2">Requirements</h2>
                <p class="text-gray-700">{{ $event->requirements }}</p>
            </div>
        @endif

        {{-- Tags --}}
        @if($event->tags)
            <div class="mb-6">
                <h2 class="text-2xl font-semibold mb-2">Tags</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $event->tags) as $tag)
                        <span class="px-3 py-1 bg-gray-200 rounded-full text-sm text-gray-700">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Organizer Info --}}
        <div class="mb-6 p-4 border rounded shadow bg-white">
            <h2 class="text-2xl font-semibold mb-2">Organizer</h2>
            <div class="flex items-center gap-4">
                @if($event->organizer->logo)
                  <img src="{{ asset('images/logos/' . $event->organizer->logo) }}" 
                    alt="{{ $event->organizer->name }}" 
                    class="w-16 h-16 object-contain rounded-full">
                @else
                  <img src="{{ asset('images/avatars/default-avatar.png') }}" 
                  alt="{{ $event->organizer->name }}" 
                  class="w-16 h-16 object-contain rounded-full">
                @endif
                <div>
                    <a href="{{ route('users.show', $event->organizer) }}" 
                       class="text-xl font-semibold text-emerald-600 hover:text-amber-500">
                        {{ $event->organizer->name }}
                    </a>
                    @if($event->organizer->company_name)
                        <p class="text-gray-600 text-sm">{{ $event->organizer->company_name }}</p>
                    @endif
                    @if($event->organizer->website)
                        <a href="{{ $event->organizer->website }}" target="_blank" 
                           class="text-sm text-blue-500 hover:underline">{{ $event->organizer->website }}</a>
                    @endif
                </div>
            </div>
        </div>

        @if($canApply)
            <form action="{{ route('events.apply', $event) }}" method="POST">
                @csrf
                <button class="bg-emerald-600 hover:bg-amber-500 text-white py-3 px-6 rounded shadow transition w-full md:w-auto">
                    Apply to Event
                </button>
            </form>
        @else
            @auth
                <p class="text-gray-500 text-sm">Only volunteers can apply for this event.</p>
            @else
                <p class="text-gray-500 text-sm">Login as a volunteer to apply for this event.</p>
            @endauth
        @endif

        {{-- Back Button --}}
        <div class="mt-8">
            <a href="{{ route('events.index') }}" 
            class="inline-flex items-center gap-2 bg-white text-gray-800 py-2 px-4 rounded shadow hover:shadow-lg hover:bg-emerald-50 transition">
                <!-- Left arrow icon -->
                <span class="text-lg">&larr;</span>
                <span>Back to Events</span>
            </a>
        </div>
        
    </div>
</x-layout>
