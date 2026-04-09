@props(['event'])

<div class="rounded-lg shadow-md bg-white hover:shadow-lg transition hover:bg-emerald-50 flex flex-col h-full">
    
    {{-- Event Photo --}}
    @if($event->photo)
        <img src="{{ asset('storage/' . $event->photo) }}" 
            alt="{{ $event->title }}" 
            class="w-full h-36 object-cover rounded-t mb-3">
    @endif

    {{-- Content --}}
    <div class="flex-1 flex flex-col justify-between p-4">
        <div>
            {{-- Event Title --}}
            <h2 class="text-xl font-semibold mb-1 hover:text-amber-500 transition-colors duration-200 cursor-pointer">
                {{ $event->title }}
            </h2>

            {{-- Event Dates --}}
            <p class="text-gray-600 text-sm mb-2">
                {{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}
            </p>

            {{-- Description --}}
            <p class="text-gray-700 mb-2">{{ Str::limit($event->description, 80) }}</p>

            {{-- Location and Price --}}
            <p class="text-sm mb-2">
                <strong>Location:</strong> {{ $event->location ?? 'TBD' }}
                @if($event->is_free)
                    <span class="text-emerald-600 font-semibold ml-2">Free</span>
                @else
                    <span class="text-amber-600 font-semibold ml-2">${{ number_format($event->price, 2) }}</span>
                @endif
            </p>

            {{-- Capacity & Participants --}}
            <p class="text-sm text-gray-400 mb-2">
                Capacity: {{ $event->capacity ?? 'Unlimited' }} |
                Participants: {{ $event->participantCount() }}
            </p>
        </div>

        {{-- Bottom Buttons: Details + Save/Unsave --}}
        <div class="flex items-center justify-between mt-3">

            {{-- Details Button --}}
            <a href="{{ route('events.show', $event) }}" class="flex items-center gap-2 bg-emerald-600 hover:bg-amber-500 text-white py-2 px-4 rounded text-sm transition-colors duration-200 transform hover:scale-105">
                <i class="fa-solid fa-circle-info"></i>
                Details
            </a>

            {{-- Save/Unsave Button (Volunteers Only) --}}
            @auth
                @if(auth()->user()->isVolunteer())
                    <form action="{{ auth()->user()->savedEvents->contains($event) ? route('events.unsave', $event) : route('events.save', $event) }}" method="POST">
                        @csrf
                        @if(auth()->user()->savedEvents->contains($event))
                            @method('DELETE')
                            <button type="submit"
                            class="flex items-center gap-2 text-white bg-amber-500 hover:bg-amber-600 border border-amber-500 px-3 py-1 rounded text-sm transition duration-200 transform hover:scale-105">
                                <i class="fa-solid fa-bookmark"></i>
                                Saved
                            </button>
                        @else
                            <button type="submit"
                                    class="flex items-center gap-2 bg-emerald-600 hover:bg-amber-500 text-white py-2 px-4 rounded text-sm transition duration-200 transform hover:scale-105">
                                <i class="fa-regular fa-bookmark"></i>
                                Save
                            </button>
                        @endif
                    </form>
                @endif
            @endauth
        </div>
    </div>
</div>