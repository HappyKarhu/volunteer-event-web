<x-layout>
<div x-data="{ showAuthModal: false }"> 

    <div class="container mx-auto mt-10 px-5">
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-md">
        {{-- Event Header --}}
        <div class="flex flex-col gap-6 border-b border-gray-100 bg-gradient-to-r from-white via-emerald-50 to-amber-50 p-6 md:flex-row md:p-8">
           
            <img src="{{ $event->photo ? asset('storage/' . $event->photo) : asset('storage/events/volunteerio-default.png') }}"
                alt="{{ $event->title }}" 
                class="h-64 w-full rounded-2xl object-cover shadow md:w-1/3">

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
        <div class="grid gap-6 p-6 md:grid-cols-3 md:p-8">
        <div class="space-y-6 md:col-span-2">
        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm">
            <h2 class="mb-3 text-2xl font-semibold text-emerald-600">Description</h2>
            <p class="whitespace-pre-line leading-7 text-gray-700">{{ $event->description }}</p>
        </div>

        @if($event->responsibilities)
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-3 text-2xl font-semibold text-emerald-600">Responsibilities</h2>
                <p class="whitespace-pre-line leading-7 text-gray-700">{{ $event->responsibilities }}</p>
            </div>
        @endif

        @if($event->bring_wear)
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-3 text-2xl font-semibold text-emerald-600">What to Bring / Wear</h2>
                <p class="whitespace-pre-line leading-7 text-gray-700">{{ $event->bring_wear }}</p>
            </div>
        @endif

        {{-- Requirements --}}
        @if($event->requirements)
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-3 text-2xl font-semibold text-emerald-600">Requirements</h2>
                <p class="whitespace-pre-line leading-7 text-gray-700">{{ $event->requirements }}</p>
            </div>
        @endif

        {{-- Tags --}}
        @if($event->tags)
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-3 text-2xl font-semibold text-emerald-600">Tags</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $event->tags) as $tag)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm text-emerald-700">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            </div>
        @endif
        </div>

        {{-- Organizer Info --}}
        <div class="space-y-6">
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-amber-400 px-5 py-4">
                <h2 class="text-2xl font-semibold text-white">Organizer</h2>
            </div>
            <div class="p-5">
            <div class="flex items-start gap-4">
                  <img src="{{ $event->organizer->logo_url }}" 
                    alt="{{ $event->organizer->name }}" 
                    class="h-16 w-16 rounded-full border-4 border-emerald-100 object-cover">
                <div class="space-y-2">
                    <a href="{{ route('users.show', $event->organizer) }}" 
                       class="block text-xl font-semibold text-emerald-600 hover:text-amber-500">
                        {{ $event->organizer->name }}
                    </a>
                    @if($event->organizer->company_name)
                        <p class="text-sm text-gray-600">{{ $event->organizer->company_name }}</p>
                    @endif
                    @if($event->organizer->website)
                        <a href="{{ $event->organizer->website }}" target="_blank" 
                           class="block break-all text-sm text-emerald-600 hover:text-amber-500 hover:underline">{{ $event->organizer->website }}</a>
                    @endif
                    @if($event->organizer->contact_email)
                        <a href="mailto:{{ $event->organizer->contact_email }}"
                           class="block break-all text-sm text-gray-600 hover:text-emerald-600">
                            {{ $event->organizer->contact_email }}
                        </a>
                    @endif
                </div>
            </div>
            </div>
        </div>

        @auth
            @php
                $application = auth()->user()->applications
                    ->where('event_id', $event->id)
                    ->first();
            @endphp
        @endauth
        {{-- Apply Button --}}
        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5 shadow-sm">
            @guest
                <button 
                    @click="showAuthModal = true"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-white shadow transition hover:bg-amber-500">
                    <i class="fa-solid fa-paper-plane"></i>
                    Apply to Event
                </button>
            @endguest

            @auth
                @if(auth()->user()->role === 'volunteer')

                    @if($application)
                        {{-- Already applied state --}}
                        <div class="rounded-xl bg-emerald-50 px-4 py-3 text-emerald-700 text-sm font-semibold">
                            You already applied
                            @if($application->status === 'approved')
                                • Approved
                            @elseif($application->status === 'rejected')
                                • Declined
                            @else
                                • Pending
                            @endif
                        </div>

                    @else
                        {{-- SHOW FORM ONLY IF NOT APPLIED --}}
                        <form 
                            action="{{ route('events.apply', $event) }}" 
                            method="POST" 
                            enctype="multipart/form-data"
                            class="space-y-3"
                        >
                            @csrf

                            <textarea 
                                name="message" 
                                placeholder="Why do you want to join this event?"
                                class="w-full rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            ></textarea>

                            <input 
                                type="file" 
                                name="cv" 
                                class="w-full text-sm"
                            >

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-white shadow transition hover:bg-emerald-700">
                                <i class="fa-solid fa-paper-plane"></i>
                                Apply to Event
                            </button>
                        </form>
                    @endif

                @else
                    <p class="text-sm text-gray-500">
                        Only volunteers can apply for this event.
                    </p>
                @endif
            @endauth
        </div>
        </div>
        </div>

        {{-- Back Button --}}
        <div class="border-t border-gray-100 px-6 pb-8 pt-2 md:px-8">
            <a href="{{ route('events.index') }}" 
            class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 shadow transition transform hover:scale-105 hover:bg-emerald-50 hover:shadow-lg
                    text-gray-800">
                <span class="text-lg">&larr;</span>
                <span>Back to Events</span>
            </a>
        </div>
        </div>
    </div>

    {{-- Authentication Modal --}}
    <div 
            x-show="showAuthModal"
            x-cloak
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
