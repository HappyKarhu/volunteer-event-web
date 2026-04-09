<x-layout>
<div x-data="{ showAuthModal: false }"> 

    <div class="container mx-auto mt-10 px-5">
        {{-- Event Header --}}
        <div class="flex flex-col md:flex-row gap-6 mb-6">
           
            <img src="{{ $event->photo ? asset('storage/' . $event->photo) : asset('storage/events/volunteerio-default.png') }}"
                alt="{{ $event->title }}" 
                class="w-full md:w-1/3 h-64 object-cover rounded shadow">

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
                  <img src="{{ $event->organizer->logo_url }}" 
                    alt="{{ $event->organizer->name }}" 
                    class="w-16 h-16 object-contain rounded-full">
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

        {{-- Apply Button --}}
        @guest
            <button 
                @click="showAuthModal = true"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded shadow
                    bg-emerald-600 text-white hover:bg-amber-500">
                <i class="fa-solid fa-paper-plane"></i>
                Apply to Event
            </button>
        @endguest

        @auth
            @if(auth()->user()->role === 'volunteer')
                <form action="{{ route('events.apply', $event) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded shadow
                            bg-emerald-600 text-white hover:bg-amber-500">
                        <i class="fa-solid fa-paper-plane"></i>
                        Apply to Event
                    </button>
                </form>
            @else
                <p class="text-gray-500 text-sm">
                    Only volunteers can apply for this event.
                </p>
            @endif
        @endauth

        {{-- Back Button --}}
        <div class="mt-8">
            <a href="{{ route('events.index') }}" 
            class="inline-flex items-center gap-2 px-4 py-2 rounded shadow transition transform hover:scale-105 hover:shadow-lg
                    bg-white text-gray-800 hover:bg-emerald-50">
                <span class="text-lg">&larr;</span>
                <span>Back to Events</span>
            </a>
        </div>
    </div>

    {{-- Authentication Modal --}}
    <div 
            x-show="showAuthModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
        <div 
            @click.away="showAuthModal = false"
            class="bg-emerald-50 rounded-2xl p-6 w-full max-w-md shadow-lg"
        >
            <h2 class="text-xl font-semibold mb-3">
                You need to be logged in
            </h2>

            <p class="text-gray-600 mb-4">
                To apply for events, please sign in as a volunteer.
            </p>

            <div class="flex gap-3">
                <a href="{{ route('login') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 rounded shadow transition transform hover:scale-105 hover:shadow-lg
                    bg-emerald-600 text-white hover:bg-amber-500">
                    Login
                </a>

                <a href="{{ route('register') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 rounded shadow border-2 border-emerald-600 text-emerald-600 
                         hover:bg-amber-500 hover:text-white transition transform hover:scale-105 cursor-pointer">
                    Register
                </a>
            </div>
            
            <button 
                @click="showAuthModal = false"
                class="mt-4 text-sm text-gray-500 underline w-full">
                Cancel
            </button>
        </div>
    </div>

</div>
</x-layout>
