@props(['event'])

<div x-data="{ showAuthModal: false }" class="rounded-lg shadow-md bg-white hover:shadow-lg transition hover:bg-emerald-50 flex flex-col h-full">
    
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

            {{-- Save/Unsave Button (Volunteers and Guests Only) --}}
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

            @guest
                <button type="button"
                    @click="showAuthModal = true"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-amber-500 text-white py-2 px-4 rounded text-sm transition duration-200 transform hover:scale-105">
                    <i class="fa-regular fa-bookmark"></i>
                    Save
                </button>
            @endguest
        </div>
    </div>

    {{-- Save/Unsave Button Guest redirected to login/register --}}
    @guest
        <div
            x-show="showAuthModal"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        >
            <div
                @click.away="showAuthModal = false"
                class="bg-emerald-50 rounded-2xl p-6 w-full max-w-md shadow-lg"
            >
                <h2 class="text-xl font-semibold mb-3">You need to be logged in</h2>

                <p class="text-gray-600 mb-4">
                    To save events, please log in or create an account first.
                </p>

                <div class="flex gap-3">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded shadow transition transform hover:scale-105 hover:shadow-lg bg-emerald-600 text-white hover:bg-amber-500">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded shadow border-2 border-emerald-600 text-emerald-600 hover:bg-amber-500 hover:text-white transition transform hover:scale-105 cursor-pointer">
                        Register
                    </a>
                </div>

                <button
                    type="button"
                    @click="showAuthModal = false"
                    class="mt-4 text-sm text-gray-500 underline w-full"
                >
                    Cancel
                </button>
            </div>
        </div>
    @endguest
</div>
